<?php

namespace OwenMelbz\VueImpersonate;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for VueImpersonate
 *
 * @author: Owen Melbourne
 */
class VueImpersonateServiceProvider extends ServiceProvider {

    /**
     * This will be used to register config & view
     */
    protected $packageName = 'vue_impersonate';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the config
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path($this->packageName . '.php'),
        ]);

        // Load the routes
        $this->loadRoutesFromLegacy(__DIR__ . '/routes.php');

        // Load the views
        //$this->loadViewsFrom(__DIR__ . '/resources/views', 'pwa_manifest');

        // We load the blade directive for nofollow/noindex meta tag
        Blade::directive('VueImpersonate', function () {
            return "<?php echo (new \OwenMelbz\VueImpersonate\VueImpersonateMeta)->render(); ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //$this->mergeConfigFrom( __DIR__.'/config/config.php', $this->packageName);
    }

    protected function resource_path($filename)
    {
        if (function_exists('resource_path')) {
            return resource_path($filename);
        }

        return app_path('resources/' . trim($filename, '/'));
    }

    protected function loadRoutesFromLegacy($path)
    {
        if (method_exists($this, 'loadRoutesFrom')) {
            return $this->loadRoutesFrom($path);
        }

        if (! $this->app->routesAreCached()) {
            require $path;
        }
    }

}