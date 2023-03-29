<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\App;
use App\Http\Controllers\AdminController;
use App\Models\Location;
use App\Models\LocationService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class LocationController extends AdminController
{
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

        $dataTypeContent->meta_same_as = preg_replace('/\n/', ',', App::convertArrayToMultiLineString($dataTypeContent->meta_same_as));

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    /**
     * BR(E)AD: "Edit" a single Location
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Location.
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $parent = parent::edit($request, $id);
        extract($parent->getData());

        $dataTypeContent->services = $dataTypeContent->services->map(function ($service) {
            $service->price_range = $service->price_range;

            return $service;
        });

        $dataTypeContent->meta_same_as = App::convertArrayToMultiLineString($dataTypeContent->meta_same_as);

        $slug = $this->getSlug($request);
        $view = 'voyager::bread.edit-add';
        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * BR(E)AD: "Edit"->update a single Location
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Location.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataType = self::removeServicesRelationshipRow($dataType, 'edit');

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

        // Special handling for image-picker
        $request = self::sanitizePostRequest($request);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        self::parseMetaSameAsRequestData($request);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        self::attachServices($request, $data);

        event(new BreadDataUpdated($dataType, $data));

        return redirect()
            ->route("voyager.{$dataType->slug}.edit", $id)
            ->with([
                'message' => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
    }

    /**
     * BRE(A)D: "Add"->store a single Location
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataType = self::removeServicesRelationshipRow($dataType, 'add');

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Special handling for image-picker
        $request = self::sanitizePostRequest($request);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->addRows)->validate();

        self::parseMetaSameAsRequestData($request);

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        self::attachServices($request, $data);

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            return redirect()
                ->route("voyager.{$dataType->slug}.edit", $data->id)
                ->with([
                    'message' => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Remove services relationship editRow or addRow
     */
    private static function removeServicesRelationshipRow(DataType $dataType, string $type): DataType
    {
        $colName = $type.'Rows';

        $dataType->{$colName} = $dataType->{$colName}->reduce(function ($acc, $row) {
            if ($row->field !== 'location_belongstomany_service_relationship') {
                $acc->push($row);
            }

            return $acc;
        }, new Collection());

        return $dataType;
    }

    /**
     * Detach all current services, and attach new
     */
    private static function attachServices(Request $request, Location $data): void
    {
        $services = collect($request->services)->reduce(function ($acc, $service) {
            if (isset($service['active']) && $service['active']) {
                $pivot = new LocationService([
                    'service_id' => $service['id'],
                    'price_range' => $service['price_range'],
                ]);

                for ($i = 1; $i <= 7; $i++) {
                    if (isset($service['days'][$i]['active']) && $service['days'][$i]['active']) {
                        $pivot->{'day_'.$i.'_opens_at'} = self::getHoursFromPostedData('opens_at', $service['days'][$i]);
                        $pivot->{'day_'.$i.'_closes_at'} = self::getHoursFromPostedData('closes_at', $service['days'][$i]);
                    }
                }

                $pivot->created_at = Carbon::now();

                $acc->push($pivot);
            }

            return $acc;
        }, new Collection());

        $data->services()->detach();
        foreach ($services as $service) {
            $data->services()->attach($service->service_id, $service->toArray());
        }
    }

    /**
     * Parse POST data containing some combination of `is24`, `opens_at`, and `closes_at`, return valid INT for time-of-day for closes_at or opens_at
     *
     * @param string $type `opens_at` or `closes_at`
     */
    private static function getHoursFromPostedData(string $type, array $day): ?int
    {
        return isset($day['is24']) && $day['is24'] ? ($type === 'opens_at' ? 0 : 86400) : (isset($day[$type]) ? \Carbon\Carbon::parse($day[$type])->secondsSinceMidnight() : null);
    }

    /**
     * Sanitize request data for store() and update()
     */
    private static function sanitizePostRequest(Request $request): Request
    {
        return $request->merge([
            'hero_image' => self::sanitizeHeroImage($request->get('hero_image')),
        ]);
    }

    /**
     * Sanitize Hero image URL
     */
    private static function sanitizeHeroImage(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $regexp = str_replace('/', '\/', asset(null));

        return preg_replace('/^'.$regexp.'/', '/', $value);
    }

    /**
     *  parse meta-same-as input into array
     */
    private static function parseMetaSameAsRequestData(Request $request): void
    {
        $userMetaSameAs = $request->get('meta_same_as', null);

        $userMetaSameAsArray = App::convertMultiLineStringToArray($userMetaSameAs);

        $request->merge([ 'meta_same_as' => $userMetaSameAsArray ]);
    }
}
