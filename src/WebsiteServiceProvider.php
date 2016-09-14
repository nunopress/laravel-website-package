<?php 

namespace NunoPress\Laravel\Package\Website;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class WebsiteServiceProvider
 * @package NunoPress\Laravel\Package\Website
 */
class WebsiteServiceProvider extends ServiceProvider
{
    const PATH_VIEWS    = __DIR__ . '/../resources/views';
    const PATH_CONFIG   = __DIR__ . '/../config';
    const PATH_ROUTES   = __DIR__ . '/../routes';
    const PATH_ASSETS   = __DIR__ . '/../resources/assets';

    /**
     *
     */
    public function boot()
    {
        // Load routes
        require_once self::PATH_ROUTES . '/web.php';

        // Load resources views
        $this->loadViewsFrom(self::PATH_VIEWS, 'website');

        // Publish config
        $this->publishes([
            self::PATH_CONFIG . '/website.php' => config_path('website.php')
        ], 'config');

        // Publish views
        $this->publishes([
            self::PATH_VIEWS => resource_path('views/vendor/website')
        ], 'views');

        // Publish assets
        $this->publishes([
            self::PATH_ASSETS => public_path('vendor/website')
        ], 'assets');
    }

    /**
     *
     */
    public function register()
    {
        // Merge package config with app config
        $this->mergeConfigFrom(self::PATH_CONFIG . '/website.php', 'website');

        /**
         * Register http client service
         */
        $this->app->bind('website.http_client', function () {
            return new Client(config('website.http_client', []));
        });
    }

    /**
     * @return array
     */
    public function providers()
    {
        return [];
    }
}
