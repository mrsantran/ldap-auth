<?php

namespace SanTran\LDAPAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use SanTran\LDAPAuth\Exceptions\MissingConfigurationException;
use SanTran\LDAPAuth\LDAP;

class LDAPAuthServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $config = __DIR__ . '/config/ldap_auth.php';

        // Add publishable configuration
        $this->publishes([
            $config => config_path('ldap_auth.php'),
        ], 'ldap_auth');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'ldap' as authentication method
        Auth::provider('ldap', function ($app) {

            $model = $app['config']['auth']['providers']['ldap']['model'];

            // Create a new LDAP connection
            $connection = new LDAP($this->getLDAPConfig());

            return new LDAPAuthUserProvider($connection, $model);
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'auth' ];
    }


    /**
     * @return array
     *
     * @throws MissingConfigurationException
     */
    private function getLDAPConfig()
    {
        if (is_array($this->app['config']['ldap_auth'])) {
            return $this->app['config']['ldap_auth'];
        }

        throw new MissingConfigurationException();
    }

}