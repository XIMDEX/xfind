<?php

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
