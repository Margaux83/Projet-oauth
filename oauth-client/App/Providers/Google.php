<?php


class Google extends ProviderManager
{
    protected string $redirect_url = "http://localhost:8082/googleauth-success";
    public function __construct(string $auth_url, string $api_url, string $token_url, string $redirect_url){
        new ConstantManager();
        parent::__construct(CLIENT_GOOGLEID,CLIENT_GOOGLESECRET,$auth_url, $api_url,$token_url,$redirect_url, $app="Google");
        $this->auth_url = GG_AUTH;
        $this->api_url = GG_API;
        $this->token_url = GG_TOKEN;

    }
}