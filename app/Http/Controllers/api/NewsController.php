<?php

namespace App\Http\Controllers\api;

use App\Models\News;
use Illuminate\Http\Request;
use App\Core\Utils\ArrayHelpers;
use Xfind\Http\Controllers\api\ItemController;

class NewsController extends ItemController
{

    /** @var News */
    protected $model = News::class;

    public function index()
    {
        $data = parent::index();

        // Clean title from content
        foreach ($data['docs'] as &$doc) {
            $title = $doc['name'];
            if (isset($doc['content_flat']) && !is_null($doc['content_flat'])) {
                $content =  preg_replace('/\s+/', ' ', trim($doc['content_flat']));
                if (starts_with($content, $title)) {
                    $content = trim(substr($content, strlen($title)));
                    if (starts_with($content, ".")) {
                        $content = trim(substr($content, 1));
                    }
                    $doc['content_flat'] = $content;
                    $doc['content'] = $content;
                }
            }
        }

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
        if (isset($data['content-payload'])) {
            $data = array_merge($data, $data['content-payload']);
            unset($data['content-payload']);
        }

        if (isset($data['@attributes'])) {
            $data = array_merge($data['@attributes'], $data);
            unset($data['@attributes']);
        }

        $date = null;
        if (isset($data['date'])) {
            $date = strtotime($data['date']);
            $date = date('Y-m-d H:i:s', $date);
        }

        if (isset($data['tags']) && !is_array($data['tags'])) {
            $data['tags'] = [$data['tags']];
        }

        $data['lang'] = ArrayHelpers::getProperty($data, 'language', '');
        $data['date'] = $date;

        $data['type'] = 'Xnews';

        if (!isset($data['slug'])) {
            $data['slug'] = implode(
                "/",
                array_filter(
                    [
                        str_slug(ArrayHelpers::getProperty($data, 'section', ''), '-'),
                        str_slug(ArrayHelpers::getProperty($data, 'filename', ''), '-')
                    ]
                )
            );
        }

        if (isset($data['content_flat']) && !is_array($data['content_flat'])) {
            $data['content_flat'] = preg_replace('/(<breadcrumb>.*<\/breadcrumb>)/m', '', $data['content_flat']);
            $flat = $data['content_flat'];
            $pos = strrpos($flat, '</breadcrumb>');


            if ($pos !== false) {
                $flat = substr($flat, $pos + strlen('</breadcrumb>'));
                $data['content_flat'] = $flat;
            }

            $data['content'] = $data['name'] . ' ' . $data['content_flat'];
        } else {
            $data['content'] = '';
            $data['content_flat'] = '';
        }
        return $data;
    }
}
