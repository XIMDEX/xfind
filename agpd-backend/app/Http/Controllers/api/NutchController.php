<?php

namespace App\Http\Controllers\api;

use App\Models\Nutch;

class NutchController extends ItemController
{

    /** @var Nutch */
    protected $model = Nutch::class;

    protected const MAP = [
        'language' => 'lang'
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

        foreach ($data['docs'] as $index => $docs) {
            $update = false;
            foreach ($docs as $key => $value) {
                if (array_key_exists($key, static::MAP)) {
                    $docs[static::MAP[$key]] = $value;
                    unset($docs[$key]);
                    $update = true;
                }
            }

            if ($update) {
                $data['docs'][$index] = $docs;
            }
        }

        return $data;
    }
}
