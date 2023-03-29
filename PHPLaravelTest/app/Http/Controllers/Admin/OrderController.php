<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class OrderController extends AdminController
{
    protected const BROWSE_SEARCH_FIELDS_OPTIONS = [ 'all' => true ];

    /**
     * Return array of filters for the browse view
     */
    protected static function browseFilters(): array
    {
        return [
            (object)[
                'type' => 'select_dropdown',
                'field' => 'club_status',
                'label' => 'Club Status',
                'options' => array_merge(
                    [
                        'all' => 'All',
                        'paid' => 'Paid',
                    ],
                    collect(Order::STATUSES)
                        ->filter(function ($key) {
                            return $key !== Order::STATUS_SHIPPED;
                        })
                        ->keys()
                        ->reduce(function ($acc, $key) {
                            $acc[$key] = Order::STATUSES[$key];

                            return $acc;
                        }, [])
                ),
                'defaultValue' => 'all',
                'function' => function (Builder $query, string $value): void {
                    if ($value === 'paid') {
                        $query
                            ->where('club_status', '!=', Order::STATUS_UNPAID)
                            ->where('club_status', '!=', Order::STATUS_FAILED_PAYMENT);
                    } elseif ($value !== 'all') {
                        $query
                            ->where('club_status', $value);
                    }
                },
            ],
            (object)[
                'type' => 'select_dropdown',
                'field' => 'merch_status',
                'label' => 'Merch Status',
                'options' => array_merge(
                    [
                        'all' => 'All',
                        'paid' => 'Paid',
                    ],
                    collect(Order::STATUSES)
                        ->filter(function ($key) {
                            return $key !== Order::STATUS_COMPLETED;
                        })
                        ->keys()
                        ->reduce(function ($acc, $key) {
                            $acc[$key] = Order::STATUSES[$key];

                            return $acc;
                        }, [])
                ),
                'defaultValue' => 'all',
                'function' => function (Builder $query, string $value): void {
                    if ($value === 'paid') {
                        $query
                            ->where('merch_status', '!=', Order::STATUS_UNPAID)
                            ->where('merch_status', '!=', Order::STATUS_FAILED_PAYMENT);
                    } elseif ($value !== 'all') {
                        $query
                            ->where('merch_status', $value);
                    }
                },
            ],
            (object)[
                'type' => 'select_dropdown',
                'field' => 'shipping_required',
                'label' => 'Shipping Required',
                'options' => [
                    'all' => 'All',
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
                'defaultValue' => 'all',
                'function' => function (Builder $query, string $value): void {
                    if ($value === 'yes') {
                        $query->whereHas('products', function ($query) {
                            $query->whereHas('category', function ($query) {
                                $query
                                    ->where('slug', 'wash-cards-ticket-books')
                                    ->orWhere('slug', 'branded-merchandise');
                            });
                        });
                    } elseif ($value === 'no') {
                        $query->whereDoesntHave('products', function ($query) {
                            $query->whereHas('category', function ($query) {
                                $query
                                    ->where('slug', 'wash-cards-ticket-books')
                                    ->orWhere('slug', 'branded-merchandise');
                            });
                        });
                    }
                },
            ],
            (object)[
                'type' => 'timestamp',
                'field' => 'date_from',
                'label' => 'Date From',
                'function' => function (Builder $query, ?string $value = null): void {
                    if (!$value) {
                        return;
                    }

                    try {
                        $timestamp = Carbon::parse($value)->toDateTimeString();
                    } catch (InvalidFormatException $e) {
                        return;
                    }

                    $query->where('created_at', '>=', $timestamp);
                },
            ],
            (object)[
                'type' => 'timestamp',
                'field' => 'date_to',
                'label' => 'Date To',
                'function' => function (Builder $query, ?string $value = null): void {
                    if (!$value) {
                        return;
                    }

                    try {
                        $timestamp = Carbon::parse($value)->toDateTimeString();
                    } catch (InvalidFormatException $e) {
                        return;
                    }

                    $query->where('created_at', '<=', $timestamp);
                },
            ],
        ];
    }

    /**
     * (B)READ: "Browse" Orders (list view)
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $parent = parent::index($request);
        extract($parent->getData());

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = $this->getBrowseSearch();

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        $orderByExcludedColumns = [
            'customer',
            'order_belongstomany_product_relationship',
            'club_status',
            'merch_status',
        ];

        $searchNames = $this->getBrowseSearchFields($dataType);

        $model = app($dataType->model_name);

        if ($dataType->scope && $dataType->scope !== '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $model->{$dataType->scope}();
        } else {
            $query = $model::select('*');
        }

        // Use withTrashed() if model uses SoftDeletes and if toggle is selected
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
            $usesSoftDeletes = true;

            if ($request->get('showSoftDeleted')) {
                $showSoftDeleted = true;
                $query = $query->withTrashed();
            }
        }

        $this->removeRelationshipField($dataType, 'browse');

        $filters = $this->applyBrowseFilters($query);

        if ($orderBy && in_array($orderBy, $dataType->fields())) {
            $querySortOrder = !empty($sortOrder) ? $sortOrder : 'desc';
            $dataTypeContent = call_user_func([
                $query->orderBy($orderBy, $querySortOrder),
                'get',
            ]);
        } elseif ($model->timestamps) {
            $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), 'get']);
        } else {
            $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), 'get']);
        }

        // Filter by search query
        if ($search->value) {
            // Hit Gateway with search query (caches all results in a single request, and allows us to use filter via string comparison directly on the results instead of via accessor attributes which would in turn hit gateway for each record)
            $gatewayUsers = User::searchGateway($search->value);

            $dataTypeContent = $dataTypeContent->filter(function ($row) use ($search, $gatewayUsers) {
                $gatewayUser = collect($gatewayUsers)->firstWhere('email', $row->customer_email) ?? null;
                if (!stristr((string)$row->id, $search->value) && !stristr((string)$row->customer_email, $search->value) && !stristr($gatewayUser->name ?? '', $search->value)) {
                    return false;
                }

                return true;
            });
        }

        // Paginate
        if ($getter === 'paginate') {
            $perPage = $model->getPerPage();
            $page = $request->get('page', 1);
            $dataTypeContent = new LengthAwarePaginator($dataTypeContent->skip(($page - 1) * $perPage)->take($perPage), $dataTypeContent->count(), $perPage, $page, [
                'path' => route('voyager.orders.index'),
                'pageName' => 'page',
            ]);
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

        if ($sortOrder && $orderColumn) {
            $orderColumn[0][1] = $sortOrder;
        }

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$dataType->slug.browse")) {
            $view = "voyager::$dataType->slug.browse";
        }

        self::saveQueryString($dataType, 'index');

        return Voyager::view($view, compact([
            'actions',
            'dataType',
            'dataTypeContent',
            'filters',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'orderByExcludedColumns',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn',
        ]));
    }

    /**
     * B(R)EAD: "Read" a single Order
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Location.
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $parent = parent::show($request, $id);
        extract($parent->getData());

        $slug = $this->getSlug($request);
        $view = 'voyager::bread.read';
        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        // Get user information from Gateway
        if ($dataTypeContent->user ?? null) {
            $user = $dataTypeContent->user->getFromGateway();
            if ($user) {
                $dataTypeContent->user->first_name = $user->first_name;
                $dataTypeContent->user->last_name = $user->last_name;
                $dataTypeContent->user->phone = $user->phone.'!!!';
            }
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    /**
     * BR(E)AD: "Edit" a single Order
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Location.
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $parent = parent::edit($request, $id);
        extract($parent->getData());

        $slug = $this->getSlug($request);
        $view = 'voyager::bread.edit-add';
        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        // Get user information from Gateway
        if ($dataTypeContent->user ?? null) {
            $user = $dataTypeContent->user->getFromGateway();
            if ($user) {
                $dataTypeContent->user->first_name = $user->first_name;
                $dataTypeContent->user->last_name = $user->last_name;
            }
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * BR(E)AD: "Edit"->update a single Order
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Order.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataType = self::removeProductsRelationshipRow($dataType, 'edit');

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope !== '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        $originalAttributes = $data->getAttributes();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $wasShippingNotificationSent = false;
        if ($originalAttributes['merch_status'] === Order::STATUS_SHIPPED && $request->get('merch_status') === Order::STATUS_PENDING) {
            Order::findOrFail($data->id)->update([ 'shipped_at' => null ]);
        } elseif ($originalAttributes['merch_status'] !== Order::STATUS_SHIPPED && $request->get('merch_status') === Order::STATUS_SHIPPED) {
            $now = Carbon::now();

            $order = Order::findOrFail($data->id);
            $order->shipped_at = $now;

            if ($request->get('send_shipping_notification') === '1' && !$data->shipping_notification_sent_at && !($order->user && !$order->user->notification_pref_orders)) {
                $name = $order->user->first_name ?? $order->customer_first_name;
                $email = $order->user_email ?? $order->user->email;

                Mail::send(
                    [
                        'html' => 'emails.order-shipped-html',
                        'text' => 'emails.order-shipped-text',
                    ],
                    [
                        'name' => $name,
                        'email' => $email,
                        'order' => $order,
                        'user' => $order->user ?? null,
                    ],
                    function ($message) use ($email, $name) {
                        $message->to($email, $name)
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->subject('Your order has been shipped');
                    }
                );

                $order->shipping_notification_sent_at = $now;
                $wasShippingNotificationSent = true;
            }

            $order->save();
        }

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index", self::getSavedQueryString($dataType, 'index'));
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}".($wasShippingNotificationSent ? '. Successfully sent notification to customer.' : ''),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove products relationship editRow or addRow
     */
    private static function removeProductsRelationshipRow(DataType $dataType, string $type): DataType
    {
        $colName = $type.'Rows';

        $dataType->{$colName} = $dataType->{$colName}->reduce(function ($acc, $row) {
            if ($row->field !== 'order_belongstomany_product_relationship') {
                $acc->push($row);
            }

            return $acc;
        }, new Collection());

        return $dataType;
    }

    /**
     * Get BREAD relations data.
     */
    public function relation(Request $request)
    {
        if ($request->get('type') !== 'order_belongsto_user_relationship' || !$request->get('search')) {
            $parent = parent::relation($request);

            return $parent;
        }

        $gatewayUsers = User::searchGateway($request->get('search'));

        $results = array_reduce($gatewayUsers, function ($acc, $gatewayUser) {
            $user = User::where('email', $gatewayUser->email)->first();

            if ($user) {
                $acc[] = [
                    'id' => $user->id,
                    'text' => $gatewayUser->name,
                ];
            }

            return $acc;
        }, []);

        return response()->json([
            'results' => $results,
            'pagination' => [ 'more' => false ],
        ]);
    }

    /**
     * Export CSV
     */
    public function exportCsv(): StreamedResponse
    {
        // Check permission
        $this->authorize('read', app('App\Models\Order'));

        $dataType = DataType::where('slug', 'orders')->firstOrFail();

        $params = self::getSavedQueryString($dataType);

        $query = Order::where(DB::raw('1'), true); // Instantiate an eloquent query builder

        $this->applyBrowseFilters($query, $this->browseFilters(), $params);

        $this->applyBrowseSearch($query, $this->getBrowseSearch($params));

        $orderBy = $params['order_by'] ?? $dataType->order_column;
        $sortOrder = $params['sort_order'] ?? $dataType->order_direction;

        if ($orderBy && in_array($orderBy, $dataType->fields())) {
            $querySortOrder = !empty($sortOrder) ? $sortOrder : 'desc';
            $orders = $query->orderBy($orderBy, $querySortOrder)->get();
        } else {
            $orders = $query->orderBy('id', 'DESC')->get();
        }

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Orders (brownbear.com).csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rowsForCsv = $orders->reduce(function ($acc, $order): array {
            $homeCarWash = Location::find($order->user->home_location_id ?? null);

            foreach ($order->products as $product) {
                $orderProduct = $product->pivot;

                $row = [
                    'Order ID' => $order->id,
                    'Order Date & Time' => Carbon::parse($order->created_at)->format(config('format.date').' '.config('format.time')),
                    'Order Total' => number_format($order->total ?? 0, 2),
                    'Order Shipping Charge' => number_format($order->shipping_price ?? 0, 2),

                    'Merch Status' => Order::STATUSES[$order->merch_status] ?? $order->merch_status,
                    'Club Status' => Order::STATUSES[$order->club_status] ?? $order->club_status,

                    'Customer First Name' => $order->user->first_name ?? $order->customer_first_name,
                    'Customer Last Name' => $order->user->last_name ?? $order->customer_last_name,
                    'Customer Email' => $order->user->email ?? $order->user_email ?? null,
                    'Customer Phone' => $order->user->phone ?? null,
                    'Shipping First Name' => $order->shipping_first_name,
                    'Shipping Last Name' => $order->shipping_last_name,
                    'Shipping Address' => $order->shipping_address,
                    'Shipping City' => $order->shipping_city,
                    'Shipping State' => $order->shipping_state,
                    'Shipping Zip' => $order->shipping_zip,
                    'Shipping Phone' => $order->shipping_phone,

                    'Customer Home Car Wash' => $homeCarWash ? $homeCarWash->site_number : null,
                    'Shipping Notification Sent' => $order->shipping_notification_sent_at ? Carbon::parse($order->shipping_notification_sent_at)->format(config('format.date').' '.config('format.time')) : '',
                    'Transaction ID' => $order->transaction_id,
                    'Transaction Error' => $order->transaction_error,

                    'Product ID' => $orderProduct->product_id,
                    'Product Name' => $product->name,
                    'Product Price Ea' => number_format(($orderProduct->purchase_price_ea ?? 0) / 100, 2),
                    'Qty' => $orderProduct->qty,
                    'Coupon Code' => $orderProduct->coupon_code,
                    'Discount' => number_format(($orderProduct->discount ?? 0) / 100, 2),
                    'Tax' => number_format(($orderProduct->tax ?? 0) / 100, 2),
                    'Line Item Total' => number_format(($orderProduct->total ?? 0) / 100, 2),
                ];

                $acc[] = $row;
            }

            return $acc;
        }, []);

        $columns = array_keys($rowsForCsv[0] ?? []);

        $makeCsv = function () use ($rowsForCsv, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rowsForCsv as $order) {
                fputcsv($file, $order);
            }

            fclose($file);
        };

        return Response::stream($makeCsv, 200, $headers);
    }

    /**
     * Print an invoice
     */
    public function print(Request $request): View
    {
        if (!$order = Order::find($request->id)) {
            abort(404);
        }

        return view('orders.invoice')->with([ 'order' => $order ]);
    }
}
