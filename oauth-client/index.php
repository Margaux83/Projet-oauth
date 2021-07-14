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

    function handleSuccessServer()
    {
        ["code" => $code, "state" => $state] = $_GET;
        // ECHANGE CODE => TOKEN
        getUser([
            "grant_type" => "authorization_code",
            "code" => $code
        ]);
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
        handleSuccessServer();
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
    case '/ddauth-success':
        new ConstantManager();
        $success = new ProviderManager(CLIENT_DDID, CLIENT_DDSECRET, DD_AUTH,DD_API, DD_TOKEN, "http://localhost:8082/ddauth-success");
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
}

