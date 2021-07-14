<?php

/**
 * Class Discord
 */
class Discord extends ProviderManager
{
    protected string $redirect_url = "http://localhost:8082/ddauth-success";
    public function __construct(string $auth_url, string $api_url, string $token_url, string $redirect_url){
        new ConstantManager();
        parent::__construct(CLIENT_DDID,CLIENT_DDSECRET,$auth_url, $api_url,$token_url,$redirect_url);
        $this->auth_url = DD_AUTH;
        $this->api_url = DD_API;
        $this->token_url = DD_TOKEN;

    }
}