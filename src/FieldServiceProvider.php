<?php

namespace ArboryNova\GroupedBooleanGroup;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'grouped-boolean-group');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/grouped-boolean-group'),
        ], 'grouped-boolean-group-lang');

        Nova::serving(function (ServingNova $event) {
            Nova::script('grouped-boolean-group', __DIR__ . '/../dist/js/field.js');
            Nova::style('grouped-boolean-group', __DIR__ . '/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
