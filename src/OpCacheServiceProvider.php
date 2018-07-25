<?php
namespace OpCache;

use OpCache\Command\OpCache\Reset;
use OpCache\Command\OpCache\Status;
use OpCache\Http\Middleware\RequestMiddleWare;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

/**
 *
 * Class OpCacheServiceProvider
 *
 * @package OpCache
 */
class OpCacheServiceProvider extends ServiceProvider
{
    /**
     * @var $configFilePath
     */
    protected $configFilePath = '/../config/opcache.php';
    /**
     * @var $routeFilePath
     */
    protected $routeFilePath = '/Http/routes.php';
    /**
     * This function is to register the opCache route and bind this route with middle ware and
     * Register the console command
     *
     *
     */
    public function register()
    {
        $this->registerOpCacheCommand();

        $this->publishConfig();
    }

    /**
     * This function is to boot up the route file
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.$this->configFilePath, 'opCache');
        $this->bindRoute();
    }

    private function registerOpCacheCommand()
    {
        $this->commands([
            Reset::class,
            Status::class,
        ]);
    }

    private function publishConfig()
    {
        $this->publishes([
            __DIR__.$this->configFilePath => config_path('opcache.php'),
        ], 'config');
    }

    private function bindRoute()
    {
        $this->app->router->group([
            'middleware'    => [RequestMiddleWare::class],
            'prefix'        => config('opcache.route_group'),
            'namespace'     => 'OpCache\Http\Controller',
        ], function ($router) {
            require __DIR__.$this->routeFilePath;
        });
    }
}
