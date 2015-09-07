<?php namespace Acme\Test;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;


class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->handleConfigs();
        // $this->handleMigrations();
        // $this->handleViews();
        // $this->handleAssets();
        // $this->handleTranslations();
        // $this->handleRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        // Bind any implementations that you want resolved from the ioc.

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

        return [];
    }

    private function handleConfigs()
    {

        $configPath = __DIR__ . '/../config/test.php';
        $this->publishes([$configPath => config_path('test.php')], 'config');
        $this->mergeConfigFrom($configPath, 'test');
    }

    private function handleTranslations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'test');
        $this->publishes([__DIR__ . '/../lang' => base_path('resources/lang/vendor/test')], 'translation');
    }

    private function handleViews()
    {

        $this->loadViewsFrom(__DIR__ . '/../views', 'test');
        $this->publishes([__DIR__ . '/../views' => base_path('resources/views/vendor/test')], 'view');
    }

    private function handleMigrations()
    {

        $this->publishes([__DIR__ . '/../migrations' => base_path('database/migrations')], 'migration');
    }

    private function handleAssets()
    {

        $this->publishes([__DIR__.'/../public' => public_path('vendor/test')], 'public');
    }

    private function handleRoutes()
    {

        include __DIR__ . '/../routes.php';
    }

}