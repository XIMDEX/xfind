<?php

namespace App\Http\Controllers\api;

class NewsController extends ItemController
{
    protected $className = '\Xfind\models\News';

    public function index()
    {
        $params = $this->getQueryParams();
        $data = $this->model->find()->paginate();
        return $data;
    }

    public function show($slug)
    {
        $data = $this->model->one("slug:{$slug}");

        if (!$data) {
            abort(404);
        }

        return $data;
    }
}
