<?php
/**
 * Copyright (C) 2019 Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


namespace App\Http\Controllers\api;

use App\Models\News;
use Illuminate\Http\Request;
use Xfind\Core\Utils\ArrayHelpers;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NewsController extends BaseController
{

    /** @var News */
    protected $model = News::class;

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
            $document =  array_key_exists('id', $data) ? "document with id {$data['id']} and " : '';
            Log::channel('error_news')->error("Failed to index: {$document}errors: " . implode('; ', $errors));
        }

        return response($res[0], $res[1]);
    }

    public function delete($id)
    {
        try {
            $result = $this->model->delete($id);
        } catch (ModelNotFoundException $e) {
            $result = false;
        }
        
        $res = $result ? ['deleted', 200] : ['fail', 400];

        if (!$result) {
            Log::channel('error_news')->error("Failed to delete: document with id: {$id}");
        }
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

        $data['lang'] = strtolower(ArrayHelpers::getProperty($data, 'language', ''));
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
