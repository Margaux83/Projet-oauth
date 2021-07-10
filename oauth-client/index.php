<?php
include "HomePage.php";
include "ConstantManager.php";


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

function handleLogin()
{


    echo "<!doctype html>
<html lang='en'>
<head>
    <!-- Required meta tags -->
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

<link rel='stylesheet' href='style.css'>
    <!-- Bootstrap CSS -->
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

</head>
    <title>Hello, world!</title>
</head>
<nav class='navbar navbar-expand-lg navbar-dark' style='background-color: crimson; color: white'>
    <div class='container-fluid'>
        <a class='navbar-brand' href='#'>Oauth Project</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>     
        <span class='navbar-toggler-icon'></span>
        </button>
    </div>
</nav>
<body>
<h1>Login with Auth-Code</h1>
 <a href='http://localhost:8081/auth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_ID
        . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a>
<div class='container'>
    <a class='btn btn-social btn-facebook link-in-popup' href='https://www.facebook.com/v2.10/dialog/oauth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_FBID
        . "&scope=email&state=dsdsfsfds&redirect_uri=http://localhost:8082/fbauth-success'>
    <i class='fa fa-facebook'></i> Sign in with Facebook
</a><br><br>
 <a class='btn btn-social btn-github link-in-popup' href='https://github.com/login/oauth/authorize?"
        . "response_type=code"
        . "&client_id=" . CLIENT_GHID
        . "&scope=read:user&state=dsdsfsfds&redirect_uri=http://localhost:8082/ghauth-success'>
    <i class='fa fa-github'></i> Sign in with Github
</a><br><br>
 <a class='btn btn-social btn-google link-in-popup' href=https://accounts.google.com/o/oauth2/v2/auth?scope=email&access_type=offline&response_type=code&redirect_uri=http://localhost:8082/googleauth-success&client_id=". CLIENT_GOOGLEID .">
    <i class='fa fa-google'></i> Sign in with Google
</a><br><br>

</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='nonymous'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
</body>
</html>";

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
    $error    = \curl_error($ch);
    $errno    = \curl_errno($ch);

    if (\is_resource($ch)) {
        \curl_close($ch);
    }

    if (0 !== $errno) {
        throw new \RuntimeException($error, $errno);
    }

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
        handleLogin();
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
        http_response_code(404);
}

