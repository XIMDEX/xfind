<?php

namespace App\Http\Controllers\api;

use App\Models\Resolutions;
use App\Core\Utils\DateHelpers;
use Xfind\Http\Controllers\api\ItemController;

class resolutionController extends ItemController
{
    /** @var News */
    protected $model = Resolutions::class;

    public function __construct()
    {
        $resolutions = config('xfind.solr.resolutions');
        config(['xfind.solr.core' => $resolutions]);
        parent::__construct();
    }

    protected function prepareData(&$data)
    {
        $data = array_merge($data, $data['content-payload'], $data['metadata']);
        unset($data['content-payload']);
        unset($data['metadata']);

        $data['date'] = DateHelpers::parse($data['date']);

        $data['content'] = implode(' ', [$data['name'], $data['title'] ?? '', $data['resolution_number'] ?? '', $data['content_flat'] ?? '']);

        parent::prepareData($data);
        return $data;
    }
}
