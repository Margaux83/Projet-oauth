<?php




class Facebook extends ProviderManager
{
    protected string $redirect_url = "http://localhost:8082/fbauth-success";
    public function __construct(string $auth_url, string $api_url, string $token_url, string $redirect_url){
        new ConstantManager();
        parent::__construct(CLIENT_FBID,CLIENT_FBSECRET,$auth_url, $api_url,$token_url,$redirect_url, $app="Facebook");
        $this->auth_url = FB_AUTH;
        $this->api_url = FB_API;
        $this->token_url = FB_TOKEN;

    }
}