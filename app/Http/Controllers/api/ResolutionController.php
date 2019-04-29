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

use App\Models\Resolutions;
use Xfind\CoreUtils\DateHelpers;
use App\Http\Controllers\Controller as BaseController;

class ResolutionController extends BaseController
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
