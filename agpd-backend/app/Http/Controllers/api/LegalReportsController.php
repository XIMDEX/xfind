<?php

namespace App\Http\Controllers\api;

use App\Models\LegalReport;
use App\Core\Utils\DateHelpers;

class LegalReportsController extends ItemController
{
    /** @var News */
    protected $model = LegalReport::class;

    public function __construct()
    {
        $legalreports = config('xfind.solr.legalreports');
        config(['xfind.solr.core' => $legalreports]);
        parent::__construct();
    }

    protected function prepareData(&$data)
    {
        $data = array_merge($data, $data['content-payload'], $data['metadata']);
        unset($data['content-payload']);
        unset($data['metadata']);

        $data['date'] = DateHelpers::parse($data['date']);

        $data['content'] = implode(' ', [$data['name'], $data['title'] ?? '', $data['code'] ?? '', $data['content_flat'] ?? '']);

        parent::prepareData($data);
        return $data;
    }
}