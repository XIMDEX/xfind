<?php

namespace App\Http\Controllers\api;

use Solarium\Client;

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

    public function update()
    {

        $this->model->createOrUpdate([
            "id" => "5",
            "slug" => "test_noticia",
            "type" => "noticia",
            "author" => "Autor 1",
            "content_flat" => "Texto plano",
            "content_render" => "<p>Texto html</p>",
            "date" => "2018-03-03T01:20:23Z",
            "name" => "Esta noticia tiene nombre",
            "section" => "Seccion",
            "state" => "publish",
            "tags" => ["Tag1", "Tag2"]
        ]);
    }
}
