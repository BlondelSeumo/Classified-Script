<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace             = 'App\Http\Controllers';
    protected $mainNamespace         = 'App\Http\Controllers\Main';
    protected $managerNamespace      = 'App\Http\Controllers\Manager';
    protected $moderatorNamespace    = 'App\Http\Controllers\Moderator';
    protected $adminNamespace        = 'App\Http\Controllers\Administrator';
    protected $installationNamespace = 'App\Http\Controllers\Installation';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapMainRoutes();

        $this->mapManagerRoutes();

        $this->mapModeratorRoutes();

        $this->mapAdminRoutes();

        $this->mapWebRoutes();

        $this->mapInstallationRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web', 'cors', 'throttle:3,10')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api', 'cors', 'throttle:60,1')
             ->prefix('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "main" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapMainRoutes()
    {
        Route::namespace($this->mainNamespace)
             ->prefix('api')
             ->middleware('api', 'throttle:60,1')
             ->group(base_path('routes/main.php'));
    }

    /**
     * Define the "manager" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapManagerRoutes()
    {
        Route::namespace($this->managerNamespace)
             ->middleware('auth:api', 'throttle:60,1')
             ->prefix('api/manager')
             ->group(base_path('routes/manager.php'));
    }

    /**
     * Define the "moderator" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapModeratorRoutes()
    {
        Route::namespace($this->moderatorNamespace)
             ->middleware('auth:api', 'moderator', 'throttle:60,1')
             ->prefix('api/moderator')
             ->group(base_path('routes/moderator.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::namespace($this->adminNamespace)
             ->middleware('auth:api', 'administrator', 'throttle:60,1')
             ->prefix('api/administrator')
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "installation" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapInstallationRoutes()
    {
        Route::namespace($this->installationNamespace)
             ->prefix('api/installation')
             ->group(base_path('routes/installation.php'));
    }
}
