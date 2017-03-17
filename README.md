Laravel 5 LDAP Authentication [Laravel 5.2+](http://laravel.com/)  
======================

[![Total Downloads](https://img.shields.io/packagist/dt/santran/ldap-auth.svg)](https://packagist.org/packages/santran/ldap-auth)
[![Paypal Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](http://paypal.me/MrSanTran)

## Installation

### Step 1: Install via composer
```
    composer require santran/ldap-auth:dev-master
```

### Step 2: Add the Service Provider

Modify your `config/app.php` file and add the service provider to the providers array.

```php
    SanTran\LDAPAuth\LDAPAuthServiceProvider::class,
```

### Step 3: Publish the configuration file by running:

```
    php artisan vendor:publish --tag="ldap_auth"
```

Now you're all set!

## Configuration

### Step 1: Tweak the basic authentication

Setup LDAP Server config `config/ldap_auth.php`.

```php

return [
    'suffix' => '@127.0.0.1',

    /*
    |--------------------------------------------------
    | Domain Controllers
    |--------------------------------------------------
    |
    | The domain controllers option is an array of servers located on your
    | network that serve Active Directory. You can insert as many servers or
    | as little as you'd like depending on your forest (with a minimum of one).
    |
    */
    'domain_controller' => [
        '127.0.0.1'
    ],

    /*
    |--------------------------------------------------
    | Base Distinguished Name
    |--------------------------------------------------
    |
    | The base distinguished name is the base distinguished name you'd like
    | to perform operations on. An example base DN would be DC=dns,DC=example,DC=local.
    |
    | If none defined, then it will try to find it automatically by querying your server.
    | It's highly recommended to include it to limit queries executed per request.
    |
    */
    'base_dn' => 'DC=aitldap,DC=com',

    /*
    |--------------------------------------------------
    | Group Distinguished Name
    |--------------------------------------------------
    |
    | Permission login to this tool
    |
    */
    
    'group_dn' => 'CN=tms,OU=tools,DC=aitldap,DC=com',

    /*
    |--------------------------------------------------
    | Search Filter
    |--------------------------------------------------
    |
    | The filter option defines (you guessed it) on what filter to execute a query on.
    | The default filter is "uid". For more information please check
    | msdn.microsoft.com/En-US/library/aa746475.aspx
    |
    */
    
    'search_filter' => 'uid',

    /*
    |--------------------------------------------------
    | Search Fields
    |--------------------------------------------------
    |
    | The fields options defined what fields you want the be returned on a successful
    | query result. Note: The distinguished name is always returned.
    |
    */
    'search_fields' => [
        'cn',
        'gidNumber',
        'uid',
    ],
    
    'read_user_record' => true,
    
    'mapping_field' => "username",
    
    /*
    |--------------------------------------------------
    | Backup Rebinding
    |--------------------------------------------------
    |
    | This options indicates to use the host names sequentially. This package will try
    | to connect to the first domain controller. If it's not reachable the next DC
    | will be tried.
    |
    | If this option is set to false load balancing will be used instead for multiple DC.
    |
    */
    'backup_rebind' => true,

    /*
    |--------------------------------------------------
    | SSL & TLS
    |--------------------------------------------------
    |
    | One of these options are recommended if you have the ability to connect to your server
    | securely. Ensure that only one option can be true. The other one must be false.
    |
    */
    'ssl' => false,
    'tls' => false,

    /*
    |--------------------------------------------------------------------------
    | Administrator Username & Password
    |--------------------------------------------------------------------------
    |
    | When connecting to your AD server, an administrator username and
    | password is required to be able to query and run operations on
    | your server(s). You can use any user account that has
    | these permissions to prevent anonymous bindings.
    |
    */
    'admin_user' => 'Manager',
    'admin_pass' => '12345678',
];
```


Update your `config/auth.php` to use **ldap** as authentication and the **LDAPUser** Class.

```php
'guards' => [
  	'web' => [
  		'driver'   => 'session',
  		'provider' => 'ldap',
	],
],

'providers' => [
	'users'      => [
		'driver' => 'eloquent',
		'model'  => App\User::class,
	],

	'ldap' => [
		'driver' => 'ldap',
		'model'  => \SanTran\LDAPAuth\LDAPUser::class,
	],
]
```


### Step 2: Adjust the LDAP config to your needs

If you have run `php artisan vendor:publish --tag="ldap_auth"` you should see the  
ldap_auth.php file in your config directory. Adjust the values as you need them.

## Usage

### Authentication
```php
    if (auth()->attempt($request->only('username', 'password'))) {
        //Passed
    }
```
