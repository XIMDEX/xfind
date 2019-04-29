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

class News extends Item
{
    const TYPE = 'Xnews';

    public static $rules = [
        'slug' => ['type' => 'string', 'required' => true],
        'author' => ['type' => 'string', 'required' => true],
        'content_flat' => ['type' => 'string', 'required' => false],
        'content_render' => ['type' => 'string', 'required' => false],
        'date' => ['type' => 'string', 'required' => true],
        'id_section' => ['type' => 'string', 'required' => false],
        'id_ximdex' => ['type' => 'string', 'required' => true],
        'name' => ['type' => 'string', 'required' => true],
        'image' => ['type' => 'string', 'required' => false],
        'state' => ['type' => 'string', 'required' => true, 'values' => ['publish']],
        'lang' => ['type' => 'string', 'required' => true],
        'tags' => ['type' => 'array', 'required' => false]
    ];

    protected $highlight_fields = [
        'content'
    ];


    protected $filterFields = [
        'lang',
        'author',
        'date',
        'tags',
        'author',
        'type',
        'section'
    ];

    public function __construct()
    {
        static::$facets = array_merge(static::$facets, [
            'author',
            'date',
            'tags'
        ]);
        static::$rules = array_merge(static::$rules, parent::$rules);
        parent::__construct();
        
        $this->fields = array_merge($this->fields, [
            'section',
            'type',
            'content'
        ]);
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
