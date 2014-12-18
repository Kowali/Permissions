<?php namespace Kowali\Permissions;

use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider {

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
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->alias('Kowali\Permissions\Manager', 'kowali.permissions.manager');

        $this->app->bindShared('kowali.permissions.user', function($app){
            return new User($app['auth']->user(), $app['kowali.permissions.manager']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['kowali.permissions.manager', 'kowali.permissions.user'];
    }

}
