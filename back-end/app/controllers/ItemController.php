<?php

namespace Xfind\controllers;

use Slim\Http\Request;
use Xfind\models\Item;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;

class ItemController
{
    protected $className = '\Xfind\models\Item';
    protected $request;
    protected $response;
    protected $model = null;


    public static $paramsToModel =[
        'query' => 'setQuery',
        'limit' => 'setLimitPerPage',
        'start' => 'setStart',
        'page' => 'setPage'
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->model = new $this->className();
        $this->request = $container->get('request');
        $this->response = $container->get('response');
    }

    public function index()
    {
        $params = $this->getQueryParams();

        $data = ['facets' => $this->getFacets()] + $this->model->find($params['query'])->limit($params['limit'], $params['start'])->obtain();
        
        $result = $this->response->withHeader(
            'Content-Type',
            'application/json'
        )->write($this->setResponse($data, true));

        return $result;
    }

    protected function getFacets()
    {
        return $this->model->getFacets();
    }

    protected function getQueryParams()
    {
        $queryParams = $this->request->getQueryParams();
        $params = [
            'query' => '*:*',
            'limit' => 20,
            'start' => 0,
            'page' => 1
        ];

        foreach ($params as $param => $value) {
            if (array_key_exists($param, $queryParams)) {
                $params[$param] = $queryParams[$param];
                $this->setQueryParamsToModel($param, $queryParams[$param]);
            }
        }

        return $params;
    }

    private function setQueryParamsToModel($param, $value)
    {
        if (array_key_exists($param, static::$paramsToModel)) {
            $func = static::$paramsToModel[$param];
            $this->model->$func($value);
        }
    }

    protected function setResponse($data = [], $json = false)
    {
        $status = $this->response->getStatusCode();
        $message = $this->response->getReasonPhrase();
        
        $response = [
            'status' => 200,
            'data' => $data,
            'message' => ''
        ];

        return ($json)? json_encode($response) : $response;
    }
}
