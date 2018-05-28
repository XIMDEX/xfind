<?php

namespace App\Models;

class News extends Item
{
    const TYPE = 'Xnews';

    public static $rules = [
        'slug' => ['type' => 'string', 'required' => true],
        'author' => ['type' => 'string', 'required' => true],
        'content_flat' => ['type' => 'string', 'required' => true],
        'content_render' => ['type' => 'string', 'required' => true],
        'date' => ['type' => 'string', 'required' => true],
        'id_section' => ['type' => 'string', 'required' => true],
        'id_ximdex' => ['type' => 'string', 'required' => true],
        'name' => ['type' => 'string', 'required' => true],
        'state' => ['type' => 'string', 'required' => true, 'values' => ['publish']],
        'lang' => ['type' => 'string', 'required' => true],
        'tags' => ['type' => 'array', 'required' => false]
    ];

    public function __construct()
    {
        $this->fields = array_merge($this->fields, [
            'slug',
            'author',
            'content_flat',
            'content_render',
            'date',
            'id_section',
            'id_ximdex',
            'name',
            'section',
            'state',
            'tags',
            'type',
            'lang',
            'content'
        ]);

        $this->facets = array_merge($this->facets, [
            'author',
            'date',
            'section',
            'state',
            'tags'
        ]);

        static::$rules = array_merge(static::$rules, parent::$rules);

        parent::__construct();
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $sort = array_merge($sort, ['date' => 'desc']);

        $query = "($query) AND type:" . static::TYPE;

        return parent::find($query, $sort);
    }
}
