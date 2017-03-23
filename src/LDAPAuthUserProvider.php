<?php

namespace SanTran\LDAPAuth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use SanTran\LDAPAuth\LDAP;

class LDAPAuthUserProvider implements UserProvider
{

    /**
     * LDAP Wrapper.
     *
     * @var LDAP
     */
    protected $ldap;

    /**
     * LDAP User Class.
     *
     * @var string
     */
    protected $model;

    /**
     * @param LDAP $ldap
     * @param string $model
     */
    public function __construct(LDAP $ldap, $model)
    {
        $this->ldap = $ldap;
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->retrieveByCredentials(
                        ['username' => $identifier]
        );
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // this shouldn't be needed as user / password is in ldap
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // this shouldn't be needed as user / password is in ldap
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \SanTran\LDAPAuth\LDAPUser|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $credentials['username'];
        $result = $this->ldap->find($username);
        try {
            $user = new $this->model;
            $groups = $this->ldap->findGroups();
            if ($groups) {
                if (!isset($groups["memberuid"]) || count($groups["memberuid"]) <= 0) {
                    return null;
                }
                $total = $groups["memberuid"];
                unset($total["count"]);
                $exist = array_search($result["uid"][0], $total);
                if ($exist === false) {
                    return null;
                }
                $user->build($result);
                return $user;
            }
        } catch (\Exception $ex) { }
        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->ldap->auth(
                        $user->dn, $credentials['password']
        );
    }

}
