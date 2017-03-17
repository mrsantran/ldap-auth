<?php

namespace SanTran\LDAPAuth\Contracts;

interface UserInterface
{
    /**
     * Build an User object from the LDAP entry
     *
     * @param array $entry
     * @return void
     */
    public function build(array $entry);
    
    /**
     * Check if the LDAPUser is a member of requested group
     *
     * @param string $group
     * @return bool
     */
    public function isMemberOf($group);

}