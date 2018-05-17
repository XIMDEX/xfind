<?php

namespace App\Http\Controllers\api;

use App\Models\Nutch;
use Illuminate\Http\Request;

class NutchController extends ItemController
{

    /** @var Nutch */
    protected $model = Nutch::class;

    protected const MAP = [
        'url' => 'slug',
        'content' => 'content_flat',
        'tstamp' => 'date',
        'title' => 'name',
    ];

    public function __construct()
    {
        $nutch = config('xfind.solr.nutch');
        $variable = config(['xfind.solr.core' => $nutch]);
        parent::__construct();
    }

    public function index()
    {
        $params = $this->getQueryParams();
        $data = $this->model->find()->paginate();

        return $data;
    }
}
