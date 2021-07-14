<?php


class LoginProviders
{
    public static function handleLogin()
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
         <a class='btn btn-social btn-google link-in-popup' href=https://accounts.google.com/o/oauth2/v2/auth?scope=profile&access_type=offline&response_type=code&redirect_uri=http://localhost:8082/googleauth-success&client_id=". CLIENT_GOOGLEID .">
            <i class='fa fa-google'></i> Sign in with Google
        </a><br><br>
        <a class='btn btn-social btn-google link-in-popup' href='https://discord.com/api/oauth2/authorize?client_id=".CLIENT_DDID."&redirect_uri=http://localhost:8082/ddauth-success&response_type=code&scope=identify&state=15773059ghq9183habn'>
            <i class='fa fa-google'></i> Sign in with Discord
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
}