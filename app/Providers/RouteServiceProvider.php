<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

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

        $this->mapWebRoutes();

        //
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
        Route::middleware('web')
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
        $this->mapApiRoute('/api/users.php', '/api/v1/users');
        $this->mapApiRoute('/api/user.php', '/api/v1/user');
        $this->mapApiRoute('/api/cards.php', '/api/v1/cards');
        $this->mapApiRoute('/api/saving-plans.php', '/api/v1/saving-plans');
        $this->mapApiRoute('/api/verify.php', '/api/v1/verify');
        $this->mapApiRoute('/api/password.php', '/api/v1/password');
        $this->mapApiRoute('/api/group-saving-plans.php', '/api/v1/group-saving-plans');

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapApiRoute(string $file, string $prefix = '')
    {
        Route::prefix($prefix)
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/' . $file));
    }
}
