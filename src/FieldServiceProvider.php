<?php

namespace Arbory\NovaGroupedBooleanFieldGroup;

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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova-grouped-boolean-field-group');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/nova-grouped-boolean-field-group'),
        ], 'nova-grouped-boolean-field-group');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-grouped-boolean-field-group', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-grouped-boolean-field-group', __DIR__.'/../dist/css/field.css');
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
