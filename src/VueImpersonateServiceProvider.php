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
            __DIR__ . '/config/vue_impersonate.php' => config_path($this->packageName . '.php'),
            __DIR__ . '/resources/views/vue-impersonate.blade.php' => $this->resource_path('vue-impersonate.blade.php'),
        ]);


        // Load the views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'vue_impersonate');

        // Load the routes
        if (config('vue_impersonate.custom_route') === null) {
            $this->loadRoutesFromLegacy(__DIR__ . '/routes.php');
        }

        $this->loadBladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/config/vue_impersonate.php', $this->packageName);
    }

    protected function resource_path($filename)
    {
        if (function_exists('resource_path')) {
            return resource_path($filename);
        }

        return app_path('resources/' . trim($filename, '/'));
    }

    protected function loadBladeDirectives()
    {
        Blade::directive('vueImpersonate', function () {
            return "<?php echo (new \OwenMelbz\VueImpersonate\Services\VueImpersonateService)->render(); ?>";
        });
    }

    protected function loadRoutesFromLegacy($path)
    {
        if (method_exists($this, 'loadRoutesFrom')) {
            return $this->loadRoutesFrom($path);
        }

        if (!$this->app->routesAreCached()) {
            require $path;
        }
    }

}