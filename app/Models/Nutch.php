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


namespace App\Models;

use Xfind\Models\Item;
use Illuminate\Support\Carbon;

class Nutch extends Item
{
    protected $fields = [
        'id',
        'date',
        'tstamp',
        'anchor',
        'title',
        'url',
        'content',
        'author',
        'date',
        'tags',
        'language'
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected static $facets = [
        'author',
        'tags',
        'date'
    ];

    protected $filterFields = [
        'author',
        'tags',
        'language',
        'date'
    ];


    public function addFilter(string $query, string $name = null)
    {
        if ($name === 'language') {
            $query = "-({$name}:[* TO *] OR -{$query})";
        }

        if ($name === 'date') {
            $dates = str_replace([
                'date:',
                '(',
                ')',
                '[',
                ']'
            ], '', $query);

            $dates = explode('TO', $dates);

            foreach ($dates as &$date) {
                $date = trim($date);
            }

            $dates[count($dates) - 1] = Carbon::parse($dates[count($dates) - 1])
                ->endOfMonth()
                ->format('Y-m-d\T00:00:00\Z');
            $query = 'date:([' . implode(' TO ', $dates) . '])';
        }
        return parent::addFilter($query, $name);
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);
        return parent::find($query, $sort);
    }
}
