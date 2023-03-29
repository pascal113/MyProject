<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use ReflectionClass;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Models\DataType;

class AdminController extends VoyagerBaseController
{
    /**
     * Return the array of search parameters
     *
     * @return array
     */
    protected function getBrowseSearch(array $params = null): object
    {
        $request = $params ? collect($params) : request();

        return (object)['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
    }

    /**
     * Return an array of names of searchable columns
     */
    protected function getBrowseSearchFields(DataType $dataType, ?array $options = null): array
    {
        if (!$options && (method_exists($this, 'browseSearchFieldsOptions') || defined(static::class.'::BROWSE_SEARCH_FIELDS_OPTIONS'))) {
            $options = method_exists($this, 'browseSearchFieldsOptions') ? $this->browseSearchFieldsOptions() : (new ReflectionClass(static::class))->getConstant('BROWSE_SEARCH_FIELDS_OPTIONS');
        }

        $options = [
            'all' => $options['all'] ?? false,
            'except' => $options['except'] ?? [],
            'only' => $options['only'] ?? null,
        ];

        if ($options['all']) {
            return [];
        }

        $searchNames = [];
        if ($dataType->server_side) {
            $searchable = SchemaManager::describeTable(app($dataType->model_name)->getTable())->pluck('name')->toArray();
            $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->get();
            foreach ($searchable as $value) {
                if (in_array($value, $options['except']) || ($options['only'] && !in_array($value, $options['only']))) {
                    continue;
                }

                $field = $dataRow->where('field', $value)->first();
                $displayName = ucwords(str_replace('_', ' ', $value));
                if ($field !== null) {
                    $displayName = $field->getTranslatedAttribute('display_name');
                }
                $searchNames[$value] = $displayName;
            }
        }

        return $searchNames;
    }

    /**
     * Add filters to eloquent query
     */
    protected function applyBrowseFilters(Builder $query, ?array $filters = null, array $params = null): array
    {
        $request = $params ? collect($params) : request();

        if (!$filters && (method_exists($this, 'browseFilters') || defined(static::class.'::BROWSE_FILTERS'))) {
            $filters = method_exists($this, 'browseFilters') ? $this->browseFilters() : collect((new ReflectionClass(static::class))->getConstant('BROWSE_FILTERS'))->map(function ($filter) {
                return (object)$filter;
            })->toArray();
        }

        if (!$filters) {
            return [];
        }

        return collect($filters)->map(function ($filter) use ($query, $request) {
            $filter->defaultValue = $filter->defaultValue ?? null;
            $getter = $request instanceof Request ? 'input' : 'pull';
            $filter->value = $request->$getter('filters.'.$filter->field, $filter->defaultValue);

            if (is_callable($filter->function ?? null)) {
                call_user_func($filter->function, $query, $filter->value);
            } else {
                $query->where($filter->field, $filter->value);
            }

            return $filter;
        })->toArray();
    }

    /**
     * Add search to eloquent query
     */
    protected function applyBrowseSearch(Builder $query, object $search): void
    {
        if (is_callable($search)) {
            $search($query);

            return;
        }

        if ($search->value !== '' && $search->key && $search->filter) {
            $search_filter = $search->filter === 'equals' ? '=' : 'LIKE';
            $search_value = $search->filter === 'equals' ? $search->value : '%'.$search->value.'%';

            $query->where($search->key, $search_filter, $search_value);
        }
    }

    /**
     * Save QS params to session so that it can be used to
     * return the user to their previous state later.
     */
    protected static function saveQueryString(DataType $dataType, ?string $name = 'index'): void
    {
        Session::put(self::savedQueryStringVarName($dataType, $name), request()->only([
            'filter', // this is the "contains vs equals" component of search - not actually related to filters
            'filters',
            'key', // this is the "field" component of search
            'order_by',
            'page',
            'perPage',
            's',
            'sort_order',
            'showSoftDeleted',
        ]));
    }

    /**
     * Get saved QS params from session.
     */
    protected static function getSavedQueryString(DataType $dataType, ?string $name = 'index'): array
    {
        return Session::get(self::savedQueryStringVarName($dataType, $name)) ?? [];
    }

    /**
     * Session var name for saved query string params
     */
    private static function savedQueryStringVarName(DataType $dataType, ?string $name = 'index'): string
    {
        return "voyager::$dataType->slug.$name.saved-params";
    }

    /**
     * BR(E)AD: "Edit"->update a single record
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        parent::update($request, $id);

        return $this->redirectToUpdatedSuccess($request, $id);
    }

    /**
     * BRE(A)D: "Add"->store a single record
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->merge(['_tagging' => true]); // Tell Voyager to give us a JsonResponse

        $data = parent::store($request);

        return $this->redirectToCreatedSuccess($request, $data->getData()->data->id);
    }

    /**
     * Redirect and display success message after creating
     *
     * @param string $id|int
     */
    public function redirectToCreatedSuccess(Request $request, $id)
    {
        return $this->redirectToSuccess($request, $id, 'created');
    }

    /**
     * Redirect and display success message after updating
     *
     * @param string $id|int
     */
    public function redirectToUpdatedSuccess(Request $request, $id)
    {
        return $this->redirectToSuccess($request, $id);
    }

    /**
     * Redirect and display success message after creating or updating
     *
     * @param string $id|int
     */
    private function redirectToSuccess(Request $request, $id, ?string $eventName = 'updated'): RedirectResponse
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $baseMessage = $eventName === 'created' ? __('voyager::generic.successfully_created') : __('voyager::generic.successfully_updated');

        $model = app($dataType->model_name);
        $data = $model->findOrFail($id);
        $viewAction = $request->user()->can('edit', $data) ? 'edit' : 'show';

        return redirect()
            ->route("voyager.{$slug}.".$viewAction, $id)
            ->with([
                'message' => $baseMessage.' '.$dataType->getTranslatedAttribute('display_name_singular'),
                'alert-type' => 'success',
            ]);
    }
}
