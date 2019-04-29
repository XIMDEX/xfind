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

class Resolutions extends Item
{
    const TYPE = 'Resolution';

    public static $rules = [
        'name' => ['type' => 'string', 'required' => true],
        'slug' => ['type' => 'string', 'required' => true],
        'section' => ['type' => 'string', 'required' => true],
        'creation_date' => ['type' => 'string', 'required' => true],
        'update_date' => ['type' => 'string', 'required' => true],

        'state' => ['type' => 'string', 'required' => true, 'values' => ['publish']],
        'content_flat' => ['type' => 'string', 'required' => false],
        'content' => ['type' => 'string', 'required' => false],

        'title' => ['type' => 'string', 'required' => false],
        'pages' => ['type' => 'string', 'required' => false],

        'date' => ['type' => 'string', 'required' => true],
        'gravity_law' => ['type' => 'string', 'required' => false],
        'resolution_number' => ['type' => 'string', 'required' => false],
        'code_procedure' => ['type' => 'string', 'required' => false],
        'type_procedure' => ['type' => 'string', 'required' => false],
        'activity_group' => ['type' => 'string', 'required' => false],
        'province_denounced' => ['type' => 'string', 'required' => false],
        'theme' => ['type' => 'string', 'required' => false],
        'inflicted_item' => ['type' => 'string', 'required' => false],
        'recurred' => ['type' => 'string', 'required' => false],
        'author' => ['type' => 'string', 'required' => false],
        'type' => ['type' => 'string', 'required' => false]
    ];

    protected static $facets = [
        'gravity_law',
        'inflicted_item',
        'type_procedure',
        'activity_group',
        'date'
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected $filterFields = [
        'date',
        'gravity_law',
        'resolution_number',
        'code_procedure',
        'type_procedure',
        'activity_group',
        'province_denounced',
        'theme',
        'inflicted_item',
        'recurred',
        'author',
        'type',
    ];

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $this->addFilter('type:(' . static::TYPE . ')', 'type');

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);
        return parent::find($query, $sort);
    }
}
