<?php


namespace Dorcas\ModulesPeoplePayroll;

use Illuminate\Support\ServiceProvider;

class ModulesPeoplePayrollServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'modules-people-payroll');
        $this->publishes([
            __DIR__ . '/config/modules-people-payroll.php' => config_path('modules-people-payroll.php'),
        ], 'config');
        // adds models to directory path
        $this->publishes([
            __DIR__ . '/Models/Payroll.php' => app_path('Models/Payroll.php')
        ]);


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // add menu config
        $this->mergeConfigFrom(
            __DIR__ . '/config/navigation-menu.php', 'navigation-menu.modules-people.sub-menu'
        );
    }
}
