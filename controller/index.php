<?php

use GuzzleHttp\Client;
use Delight\Router\Router;

require __DIR__ . '/vendor/autoload.php';

const BASE_URI = 'http://agpd.lh/api/v1/';

$langs = [
    'es' => '',
    'en' => '/en'
];

$router = new Router('/searcher/controller');
$client = new Client([
    'base_uri' => BASE_URI,
    'timeout' => 15
]);

foreach ($langs as $lang) {
    $router->get($lang.'/noticias/:slug', function ($slug) use ($client) {
        $response = $client->get("noticias/$slug");
        $result = $response->getBody()->getContents();
        $result = json_decode($result);
        loadTemplate($result->content_render);
    });
}


function loadTemplate($body = '')
{
    require_once('./tpl/body.php');
}
