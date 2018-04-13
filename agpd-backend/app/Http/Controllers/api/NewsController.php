<?php

namespace App\Http\Controllers\api;

use App\Http\Models\News;
use Illuminate\Support\Facades\Request;

class NewsController extends ItemController
{

    /** @var News */
    protected $model = News::class;

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
            return response('News not found', 404);
        }

        return $data;
    }

    public function create()
    {
        $data = Request::json()->all();
        $result = $this->model->createOrUpdate($data);

        $res = $result ? ['created', 201] : ['fail', 400];
        return response($res[0], $res[1]);
    }

    public function update()
    {
        $data = Request::json()->all();
        $result = $this->model->createOrUpdate($data);

        $res = $result ? ['updated', 200] : ['fail', 400];
        return response($res[0], $res[1]);
    }

    public function delete($id)
    {
        $result = $this->model->delete($id);
        $res = $result ? ['deleted', 200] : ['fail', 400];
        return response($res[0], $res[1]);
    }
}
