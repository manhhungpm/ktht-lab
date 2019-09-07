<?php

namespace App\Soap\Request;

class Authen {
    protected $userName;
    protected $password;
    protected $domainCode;

    public function __construct($userName, $password, $domainCode)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->domainCode = $domainCode;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDomainCode()
    {
        return $this->domainCode;
    }

}