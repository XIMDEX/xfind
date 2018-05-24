<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Support\Facades\Request as StaticRequest;

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
        $this->middleware('xml');
        $this->getQueryParams();
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
        $queryParams = StaticRequest::all();

        $type = 'AND';
        if (array_key_exists('exclude', $queryParams)) {
            $type = ($queryParams['exclude'] === true | $queryParams['exclude'] === 'true') ? 'AND' : 'OR';
        }

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
            } elseif (in_array($param, $this->model->getFields())) {
                $query[] = "$param:$value";
            }
        }

        $query = count($query) > 0 ? implode($query, " $type ") : "*:*";
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
