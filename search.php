<?php
// datetimezone
date_default_timezone_set('America/Sao_Paulo');
 
// autoloader gerado pelo composer
require_once __DIR__ . "/vendor/autoload.php";
 
/* parametrizacoes */
$APP_ID = '1729246750454585';
$APP_SECRET = 'cc7f118d33ae68e038d9178e088f0080';
$APP_VERSION = '';
$ACCESSTOKEN = '';
 
// instancia do facebook
$fb = new Facebook\Facebook([
    'app_id'     => $APP_ID,
    'app_secret' => $APP_SECRET,
    'default_graph_version' => $APP_VERSION
]);

$query = "Pessoa";

try {
    // buscar key 'pasqua' em usuários
    $response = $fb->get("/search?type=user&q="{$query}"&fields=id,name,link&limit=100", $ACCESSTOKEN); 
    $users = $response->getGraphEdge();
 
    $processaResposta = function($graphEdge) use ($fb)
    {
        echo "<pre>\n";
        echo "--------------\n";
        foreach($graphEdge as $user) {
            echo "ID => " . $user['id'] . "\n";
            echo "Name => " . $user['name'] . "\n";
            echo "Link => " . @$user['link'] . "\n";
            echo "--------------\n";
        }
        echo "</pre>\n";
    };
 
    // 1 pagina
    $processaResposta($users);
     
    // 2 pagina
    $next = $fb->next($users);
    if($next) {
        $processaResposta($next);
    }
 
    // etc...
 
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}