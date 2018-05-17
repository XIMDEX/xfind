<?php

namespace App\Http\Controllers\api;

use App\Models\Nutch;

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
        config(['xfind.solr.core' => $nutch]);
        parent::__construct();
    }

    public function index()
    {
        $data = $this->model->find()->paginate();

        return $data;
    }
}
