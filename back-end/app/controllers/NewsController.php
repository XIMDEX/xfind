<?php

namespace Xfind\controllers;

use Xfind\controllers\ItemController;

class NewsController extends ItemController
{
    protected $className = '\Xfind\models\News';

    public function index()
    {
        $params = $this->getQueryParams();
        $data = $this->model->find()->paginate();

        $result = $this->response->withHeader(
            'Content-Type',
            'application/json'
        )->write($this->setResponse($data, true));

        return $result;
    }

    public function show($request)
    {
        $url = $request->getAttribute('url');

        $data = $this->model->one("slug:{$url}");

        $result = $this->response->withHeader(
            'Content-Type',
            'application/json'
        )->write($this->setResponse($data, true));
        return $result;
    }
}
