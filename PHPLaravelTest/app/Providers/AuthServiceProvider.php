<?php

namespace App\Providers;

use App\Policies\BasePolicy;
use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Facades\Voyager;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => \App\Policies\ModelPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Schema::connection('mysql');
        } catch (Exception $e) {
            $mysqlFailed = true;
        }
        if (!($mysqlFailed ?? null)) {
            // Apply BasePolicy to all data types
            if (Schema::hasTable(Voyager::model('DataType')->getTable())) {
                $dataType = Voyager::model('DataType');
                $dataTypes = $dataType->select('policy_name', 'model_name')->get();
                foreach ($dataTypes as $dataType) {
                    $policyClass = BasePolicy::class;
                    if (isset($dataType->policy_name) && $dataType->policy_name !== ''
                        && class_exists($dataType->policy_name)) {
                        $policyClass = $dataType->policy_name;
                    }

                    $this->policies[$dataType->model_name] = $policyClass;
                }
            }
        }

        $this->registerPolicies();

        //
    }
}
