<?php

namespace App\Soap\Response;

class AuthenResponse {
    protected $authenResponse;

    public function __construct($authenResponse)
    {
        $this->authenResponse = $authenResponse;
    }

    public function getAuthenResponse()
    {
        return $this->authenResponse;
    }
}