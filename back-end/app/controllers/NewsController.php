<?php

namespace Xfind\controllers;

use Xfind\controllers\ItemController;

class NewsController extends ItemController
{
    protected $className = '\Xfind\models\News';

    public function index()
    {
        $params = $this->getQueryParams();
        $data = ['facets' => $this->getFacets()] +
            $this->model->find()->paginate();

        $result = $this->response->withHeader(
            'Content-Type',
            'application/json'
        )->write($this->setResponse($data, true));

        return $result;
    }
}
