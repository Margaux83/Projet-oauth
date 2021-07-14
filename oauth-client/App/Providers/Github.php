<?php


class Github extends ProviderManager
{
    protected string $redirect_url = "http://localhost:8082/ghauth-success";
    public function __construct(string $auth_url, string $api_url, string $token_url, string $redirect_url){
        new ConstantManager();
        parent::__construct(CLIENT_GHID,CLIENT_GHSECRET,$auth_url, $api_url,$token_url,$redirect_url, $app="Github");
        $this->auth_url = GH_AUTH;
        $this->api_url = GH_API;
        $this->token_url = GH_TOKEN;

    }
}