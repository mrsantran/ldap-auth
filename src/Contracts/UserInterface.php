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

}