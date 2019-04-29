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

use App\Models\Nutch;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller as BaseController;

class NutchController extends BaseController
{

    /** @var Nutch */
    protected $model = Nutch::class;

    protected const MAP = [
        'lang' => 'language'
    ];

    public function __construct()
    {
        $nutch = config('xfind.solr.nutch');
        config(['xfind.solr.core' => $nutch]);
        parent::__construct();
    }

    public function index()
    {
        $data = parent::index();

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

        foreach ($data['facets'] as $key => $value) {
            if ($value['key'] !== 'date') {
                continue;
            }

            $data['facets'][$key]['values'] = $this->normalizeDates($value['values']);
        }

        return $data;
    }

    private function normalizeDates(array $dates)
    {
        $_dates = [];
        $count = 0;
        foreach ($dates as $date => $count) {
            $currDate = Carbon::parse($date)
                ->startOfMonth()
                ->format('Y-m-d\T00:00:00\Z');

            if (!array_key_exists($currDate, $_dates)) {
                $_dates[$currDate] = 0;
            }
            $_dates[$currDate] += $count;
        }

        return $_dates;
    }
}
