<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use App\Http\Models\Item;
use Illuminate\Support\Facades\Request;

class ItemController extends Controller
{
    protected $request;
    protected $response;
    protected $model = Item::class;


    public static $paramsToModel = [
        'query' => 'setQuery',
        'limit' => 'setLimitPerPage',
        'start' => 'setStart',
        'page' => 'setPage'
    ];

    public function __construct()
    {
        $this->model = app()->make($this->model);
    }


    public function index()
    {
        $params = $this->getQueryParams();

        $data = ['facets' => $this->getFacets()] + $this->model
                ->find($params['query'])->paginate();

        return $data;
    }

    protected function getFacets()
    {
        return $this->model->getFacets();
    }

    protected function getQueryParams()
    {
        $queryParams = Request::all();
        $params = [
            'limit' => 20,
            'start' => 0,
            'page' => 1
        ];

        $query = [];

        foreach ($queryParams as $param => $value) {
            if (array_key_exists($param, $params)) {
                $params[$param] = $queryParams[$param];
                $this->setQueryParamsToModel($param, $queryParams[$param]);
            } else if (in_array($param, $this->model->getFacets())) {
                $query[] = "$param:$value";
            }
        }

        $query = count($query) > 0 ? implode($query, " AND ") : "*:*";
        $this->setQueryParamsToModel('query', $query);

        return $params;
    }

    private function setQueryParamsToModel($param, $value)
    {
        if (array_key_exists($param, static::$paramsToModel)) {
            $func = static::$paramsToModel[$param];
            $this->model->$func($value);
        }
    }

    protected function setResponse($data = [], $json = false, $status = 200)
    {
        $this->response = $this->response->withStatus($status);
        $status = $this->response->getStatusCode();
        $message = $this->response->getReasonPhrase();

        $response = [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];

        return ($json) ? json_encode($response) : $response;
    }
}
