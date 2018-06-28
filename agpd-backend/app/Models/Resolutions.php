<?php

namespace App\Models;

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
        
        'title' => ['type' => 'string', 'required' => false],
        'pages' => ['type' => 'string', 'required' => false],

        'date' => ['type' => 'string', 'required' => true],
        'gravity_law' => ['type' => 'string', 'required' => true],
        'resolution_number' => ['type' => 'string', 'required' => true],
        'code_procedure' => ['type' => 'string', 'required' => true],
        'type_procedure' => ['type' => 'string', 'required' => true],
        'activity_group' => ['type' => 'string', 'required' => true],
        'theme' => ['type' => 'string', 'required' => true],
        'inflicted_item' => ['type' => 'string', 'required' => true],
        'recurred' => ['type' => 'string', 'required' => true],
        'author' => ['type' => 'string', 'required' => true],
        'type' => ['type' => 'string', 'required' => true]
    ];

    public function __construct()
    {
        $this->fields = array_merge($this->fields, [
            'name',
            'slug',
            'section',
            'state',
            'content_flat',
            'title',
            'pages',
            'date',
            'gravity_law',
            'resolution_number',
            'code_procedure',
            'type_procedure',
            'activity_group',
            'theme',
            'inflicted_item',
            'recurred',
            'author',
            'type'
        ]);

        $this->facets = array_merge($this->facets, [
            'date',
            'gravity_law',
            'resolution_number',
            'code_procedure',
            'type_procedure',
            'activity_group',
            'inflicted_item',
            'recurred'
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
