<?php

namespace App\Http\Controllers\api;

use App\Http\Models\News;
use Illuminate\Http\Request;

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

    public function create(Request $request)
    {
        $data = $request->all();
        if (!$data) { // IF not xml try with json
            $data = $request->json()->all();
        }

        $result = $this->model->createOrUpdate($data);

        $res = $result ? ['created', 201] : ['fail', 400];
        return response($res[0], $res[1]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        if (!$data) { // IF not xml try with json
            $data = $request->json()->all();
        }

        $this->prepareData($data);

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

    protected function prepareData(&$data)
    {
        $attr = $data['@attributes'];
        $data = array_merge($data, $data['content-payload']);
        unset($data['content-payload']);
        $data['lang'] = $attr['language'];
        $data['name'] = $attr['document-name'] ?? $attr['document_name'];
        $data['slug'] = "{$data['section']}/{$data['name']}";
    }
}
