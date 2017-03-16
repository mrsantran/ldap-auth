<?php

namespace SanTran\LDAPAuth;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use SanTran\LDAPAuth\Contracts\UserInterface as LdapUserContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User implements UserContract, AuthorizableContract, LdapUserContract
{

    use Authorizable;

    /**
     * Most of the ldap user's attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Build an User object from the LDAP entry
     *
     * @param array $entry
     *
     * @return void
     */
    public function build(array $entry)
    {
        $this->buildAttributesFromLdap($entry);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        $identifier_name = config('ldap_auth.search_filter');
        return $identifier_name;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        // this shouldn't be needed as you cannot directly access the password
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // this shouldn't be needed as user / password is in ldap
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // this shouldn't be needed as user / password is in ldap
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        // this shouldn't be needed as user / password is in ldap
    }

    /**
     * Dynamically access the user's attributes.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Dynamically set an attribute on the user.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Dynamically check if a value is set on the user.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Setting of the LdapUser attributes
     *
     * @param array $entry
     */
    private function buildAttributesFromLdap($entry)
    {
        if ($entry) {
            $key = config('ldap_auth.search_filter');
            $search_filter = isset($entry[$key][1]) ? $entry[$key] : $entry[$key][0];
            //get User info
            if (config('ldap_auth.read_user_record')) {
                $mapping_field = config('ldap_auth.mapping_field');
                $model = config("auth.providers.users.model");
                $user_info = $model::where($mapping_field, $search_filter)->first();
                if ($user_info) {
                    $this->attributes = $user_info;
                }
            }

            $this->attributes['dn'] = $entry['dn'];

            // Set the attributes accordingly to the search fields given
            foreach ($entry as $index => $key) {
                if (array_key_exists($index, config('ldap_auth.search_fields'))) {
                    $this->attributes[$key] = isset($entry[$key][1]) ? $entry[$key] : $entry[$key][0];
                }
            }
        }
    }

}
