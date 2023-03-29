<?php

namespace App\Voyager\FormFields;

use App\Models\Service;
use TCG\Voyager\FormFields\AbstractHandler;

class LocationServicesFormField extends AbstractHandler
{
    /**
     * Name of the form field type as visible to admin creating when a BREAD
     *
     * @var string
     */
    protected $codename = 'location_services';

    /**
     * Specify the view
     *
     * @param TCG\Voyager\Models\DataRow $row
     * @param TCG\Voyager\Models\DataType $dataType
     * @param object $dataTypeContent
     * @param object $options
     * @return void
     */
    public function createContent($row, $dataType, $dataTypeContent, $options) // phpcs:ignore Squiz.Commenting.FunctionComment.TypeHintMissing
    {
        $allServices = Service::all();

        return view('voyager::formfields.location_services', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'allServices' => $allServices,
        ]);
    }
}
