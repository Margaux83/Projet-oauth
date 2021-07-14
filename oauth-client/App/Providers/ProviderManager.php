<?php

class ProviderManager
{
    protected string $client_id;
    protected string $client_secret;
    protected string $auth_url;
    protected string $api_url;
    protected string $token_url;
    protected string $redirect_url;
    protected string $app;

    public function __construct($client_id, $client_secret, $auth_url, $api_url, $token_url, $redirect_url, $app=""){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->auth_url = $auth_url;
        $this->api_url = $api_url;
        $this->token_url = $token_url;
        $this->redirect_url = $redirect_url;
        $this->app = $app;
    }

    function handleSuccess($is_post=false)
    {

        ["code" => $code, "state" => $state] = $_GET;
        // ECHANGE CODE => TOKEN
       /* $result = file_get_contents($this->token_url
            . "client_id=" . $this->client_id
            . "&client_secret=" . $this->client_secret
            . "&redirect_uri=" . $this->redirect_url
            . "&grant_type=authorization_code&code={$code}");
        $token = json_decode($result, true)["access_token"];
        // GET USER by TOKEN
      */
        $context = $is_post ? stream_context_create(['http' => ['method' => 'POST', 'header'=> "Content-type: application/x-www-form-urlencoded\r\n"."Host: oauth2.googleapis.com"]]) : stream_context_create(['http'=>['header'=>'Accept: application/json']]) ;

        $params = "code=" . $code . "&client_id=". $this->client_id ."&client_secret=" . $this->client_secret. "&redirect_uri=" . $this->redirect_url . "&grant_type=authorization_code";
        $url = "{$this->token_url}".$params;
        var_dump( file_get_contents($url, false, $context));
        die();
        $response = file_get_contents($url, false, $context);
        $response_decode =  $response ? json_decode($response, true)["access_token"] : null;




        $headers = array(
            'Authorization: Bearer ' . $response_decode,
        );
        $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_USERAGENT, $config['useragent']);
        $output = curl_exec($curl);


        curl_close($curl);
        $user = json_decode($output);

        $array = get_object_vars($user);
        echo HomePage::getHome($array);

    }

}