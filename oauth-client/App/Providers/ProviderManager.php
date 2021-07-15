<?php

/**
 * Class ProviderManager
 * La classe ProviderManager sert à gérer la récupération du token et l'affichage des informations de l'utilisateur quand celui-ci s'est connecté
 */
class ProviderManager
{
    protected string $client_id;
    protected string $client_secret;
    protected string $auth_url;
    protected string $api_url;
    protected string $token_url;
    protected string $redirect_url;

    public function __construct($client_id, $client_secret, $auth_url, $api_url, $token_url, $redirect_url){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->auth_url = $auth_url;
        $this->api_url = $api_url;
        $this->token_url = $token_url;
        $this->redirect_url = $redirect_url;
    }

    function handleSuccess($is_post=false)
    {
        ["code" => $code, "state" => $state] = $_GET;

        $context = $is_post ? stream_context_create(['http' => ['header'=> "Content-type: application/x-www-form-urlencoded"]]) : stream_context_create(['http'=>['header'=>'Accept: application/json']]) ;

        $data = array(
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $this->redirect_url
        );

        /**
         * Récupération de l'access_token avec un curl init
         */
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->token_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $results = json_decode($response, true);
        $_SESSION['access_token'] = $results['access_token'];


        /**
         * Récupération des données de l'utilisateur grâce à l'access_token
         */
        $headers = array(
            'Authorization: Bearer ' .  $_SESSION['access_token'],
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
        echo HomePage::getHome($array, $_SESSION['access_token']);

    }

    /**
     * Fonction de déconnexion, renvoie sur la page de connexion, le token est donc détruit
     */
    public static function logout()
    {
        header('location: /login');
    }

}