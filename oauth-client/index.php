<?php
include "HomePage.php";
include "ConstantManager.php";
include "App/Providers/ProviderManager.php";
include("autoload.php");
include("App/Constant/ConstantUrls.php");

function getUser($params)
{
    $result = file_get_contents("http://oauth-server:8081/token?"
        . "client_id=" . CLIENT_ID
        . "&client_secret=" . CLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("http://oauth-server:8081/me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}




function handleSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    getUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
}


function handleFBSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    $result = file_get_contents("https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . CLIENT_FBID
        . "&client_secret=" . CLIENT_FBSECRET
        . "&redirect_uri=http://localhost:8082/fbauth-success"
        . "&grant_type=authorization_code&code={$code}");
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN

    $headers = array(
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    );
    $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/me?fields=id,name,email');
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
function handleGHSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    $url = file_get_contents("https://github.com/login/oauth/access_token?"
        . "client_id=" . CLIENT_GHID
        . "&client_secret=" . CLIENT_GHSECRET
        . "&code={$code}"
        . "&redirect_uri=http://localhost:8082/ghauth-success", false, stream_context_create(['http'=>['header'=>'Accept: application/json']]));

    $token = json_decode($url, true)["access_token"];

    $headers = array(
        'Authorization: token '.$token,
        'Accept: application/json'
    );
    $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/user');
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

function handleGOOGLESuccess()
{
    ["code" => $code, "state" => $state] = $_GET;

    $params = array(
        "code" => $code,
        "client_id" => CLIENT_GOOGLEID,
        "client_secret" => CLIENT_GOOGLESECRET,
        "redirect_uri" => 'http://localhost:8082/googleauth-success',
        "grant_type" => "authorization_code"
    );

    $ch = \curl_init('https://www.googleapis.com/oauth2/v4/token');

    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $params);
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($ch,\CURLOPT_CONNECTTIMEOUT ,3);
    \curl_setopt($ch,\CURLOPT_TIMEOUT, 10);

    $response = \curl_exec($ch);


    $token = json_decode($response, true)["access_token"];

    $headers = array(
        'Authorization: Bearer '.$token,
    );

    $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo?profile');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $output = curl_exec($ch);
    curl_close($ch);
    $user = json_decode($output);
    $array = get_object_vars($user);
    echo HomePage::getHome($array);
  }


  function handleError()
  {
   echo "refusé";
  }

  /**
  * AUTH_CODE WORKFLOW
  * => GET Code <- Générer le lien /auth (login)
  * => EXCHANGE Code <> Token (auth-success)
  * => GET USER by Token (auth-success)
  */

$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/login':
        new ConstantManager();
        LoginProviders::handleLogin();
        break;
    case '/auth-success':
        new ConstantManager();
        handleSuccess();
        break;
    case '/fbauth-success':
        new ConstantManager();
        $success = new ProviderManager(CLIENT_FBID, CLIENT_FBSECRET, FB_AUTH,FB_API, FB_TOKEN, "http://localhost:8082/fbauth-success");
        $success->handleSuccess(false);
        break;
    case '/ghauth-success':
        new ConstantManager();
        $success = new ProviderManager(CLIENT_GHID, CLIENT_GHSECRET, GH_AUTH,GH_API, GH_TOKEN, "http://localhost:8082/ghauth-success");
        $success->handleSuccess(false);
        break;
    case '/googleauth-success':
        new ConstantManager();
        $success = new ProviderManager(CLIENT_GOOGLEID, CLIENT_GOOGLESECRET, GG_AUTH,GG_API, GG_TOKEN, "http://localhost:8082/googleauth-success");
        $success->handleSuccess(true);
        break;
    case '/auth-error':
        handleError();
        break;
    case '/password':
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo "<form method='POST'>";
            echo "<input name='username'>";
            echo "<input name='password'>";
            echo "<input type='submit' value='Log with oauth'>";
            echo "</form>";
        } else {
            ['username' => $username, 'password' => $password] = $_POST;
            getUser([
                'grant_type' => "password",
                'username' => $username,
                'password' => $password
            ]);
        }
        break;
    default:
        http_response_code(404);
/*$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/login':
        new ConstantManager();
        LoginProviders::handleLogin();
        break;
    case '/auth-success':
        new ConstantManager();
        handleSuccess();
        break;
    case '/fbauth-success':
        new ConstantManager();
        handleFBSuccess();
        break;
    case '/ghauth-success':
        new ConstantManager();
        handleGHSuccess();
        break;
    case '/googleauth-success':
        new ConstantManager();
        handleGOOGLESuccess();
        break;
    case '/auth-error':
        handleError();
        break;
    case '/password':
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo "<form method='POST'>";
            echo "<input name='username'>";
            echo "<input name='password'>";
            echo "<input type='submit' value='Log with oauth'>";
            echo "</form>";
        } else {
            ['username' => $username, 'password' => $password] = $_POST;
            getUser([
                'grant_type' => "password",
                'username' => $username,
                'password' => $password
            ]);
        }
        break;
    default:
        http_response_code(404);*/
}

