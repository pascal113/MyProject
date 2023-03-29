<?php

declare(strict_types=1);

namespace App\Providers;

use App\Voyager\FormFields\CloudFileFormField;
use App\Voyager\FormFields\CouponProductsFormField;
use App\Voyager\FormFields\ImagePickerFormField;
use App\Voyager\FormFields\LocationServicesFormField;
use App\Voyager\FormFields\OrderCustomerFormField;
use App\Voyager\FormFields\OrderProductsFormField;
use App\Voyager\FormFields\OrderShippingAddressFormField;
use App\Voyager\FormFields\OrderStatusFormField;
use App\Voyager\FormFields\ProductCategoryAndPricesFormField;
use App\Voyager\FormFields\ProductShippingPricesFormField;
use App\Voyager\Models\Menu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('cart', CartServiceProvider::class);

        Voyager::useModel('Menu', Menu::class);

        Voyager::addFormField(CloudFileFormField::class);
        Voyager::addFormField(CouponProductsFormField::class);
        Voyager::addFormField(ImagePickerFormField::class);
        Voyager::addFormField(LocationServicesFormField::class);
        Voyager::addFormField(ProductCategoryAndPricesFormField::class);
        Voyager::addFormField(ProductShippingPricesFormField::class);
        Voyager::addFormField(OrderProductsFormField::class);
        Voyager::addFormField(OrderShippingAddressFormField::class);
        Voyager::addFormField(OrderStatusFormField::class);
        Voyager::addFormField(OrderCustomerFormField::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('not_present_with', function ($attribute, $value, $parameters, $validator) {
            foreach ($parameters as $otherAttribute) {
                if ($value && !empty($validator->getData()[$otherAttribute])) {
                    return false;
                }
            }

            return true;
        });
        Validator::replacer('not_present_with', function ($message, $attribute, $rule, $parameters, $validator) {
            return str_replace(':other', $validator->getDisplayableAttribute($parameters[0]) ?? $parameters[0], $message);
        });

        Validator::extend('valid_url_on_each_line', 'App\Rules\ValidUrlOnEachLine@passes');
    }
}
