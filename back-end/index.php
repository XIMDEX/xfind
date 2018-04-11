<?php

require 'vendor/autoload.php';

use Slim\App as Slim;
use Slim\Http\Request;
use Xfind\models\Item;
use Slim\Http\Response;

$api = new Slim([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$api->get('/test', function (Request $request, Response $response, $args = []) {
    $status = $response->getStatusCode();
    $message = $response->getReasonPhrase();
    $queryParams = $request->getQueryParams();

    $query = '*:*';
    if (array_key_exists('query', $queryParams)) {
        $query = $queryParams['query'];
    }

    $limit = 20;
    if (array_key_exists('limit', $queryParams)) {
        $limit = intval($queryParams['limit']);
    }


    $solr = new Item();

    $data = [
        'status' => $status,
        'data' => $solr->find($query)->limit($limit)->get(),
        'message' => $message
    ];


    $result = $response
        ->withHeader(
            'Content-Type',
            'application/json'
        )
        ->write(json_encode($data));
    return $result;
});

$api->get('/ping', function (Request $request, Response $response, $args = []) {
    $status = $response->getStatusCode();
    $message = $response->getReasonPhrase();

    $solr = new Item();

    $data = [
        'status' => $status,
        'data' => $solr->ping(),
        'message' => $message
    ];


    $result = $response
        ->withHeader(
            'Content-Type',
            'application/json'
        )
        ->write(json_encode($data));
    return $result;
});

$api->get('/', '\Xfind\controllers\ItemController:index')->setName('index');
$api->get('/resoluciones', '\Xfind\controllers\ResolutionController:index')->setName('resolution');
$api->get('/noticias', '\Xfind\controllers\NewsController:index')->setName('news');

$api->run();
