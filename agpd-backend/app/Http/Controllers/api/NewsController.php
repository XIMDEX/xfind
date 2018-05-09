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
        $slug = str_replace('@@_@@', '/', $slug);
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
        ['valid' => $valid, 'errors' => $errors] = $this->model->load($data)->validate();
        if ($valid) {
            $result = $this->model->createOrUpdate();
            $res = $result ? ['updated', 200] : ['fail', 400];
        } else {
            $res = [json_encode(['errors' => $errors]), 400];
        }

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
        //$attr = $data['@attributes'];
        $data = array_merge($data, $data['content-payload']);
        unset($data['content-payload']);
        $data = array_merge($data, $data['@attributes']);
        unset($data['@attributes']);

        $data['lang'] = ArrayHelpers::getProperty($data, 'language', '');
        /*$data['name'] = ArrayHelpers::getProperty($data, 'name', '');*/
        $data['slug'] = implode("/", array_filter([ArrayHelpers::getProperty($data, 'section', ''), ArrayHelpers::getProperty($data, 'name', '')]));
        $data['content'] = $data['content_flat'];
        return $data;
    }
}


class ArrayHelpers
{
    public static function getProperty(array $data, string $property, $default = '', $type='string')
    {
        return (isset($data[$property]) && ($value = $data[$property]) && gettype($value) === $type) ? $value : $default;
    }
}
