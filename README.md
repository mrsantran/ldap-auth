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

Update your `config/auth.php` to use **ldap** as authentication and the **User** Class.

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
		'model'  => \SanTran\LDAPAuth\User::class,
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