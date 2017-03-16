<?php

namespace SanTran\LDAPAuth\Connections;

class SingleDomainController extends DomainController
{

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->protocol . reset($this->domain_controller);
    }
}