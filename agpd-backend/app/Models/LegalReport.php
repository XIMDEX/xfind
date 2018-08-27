<?php

namespace App\Models;

class LegalReport extends Item
{

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
        'code' => ['type' => 'string', 'required' => false],
        'theme' => ['type' => 'string', 'required' => false],
        'subtheme' => ['type' => 'string', 'required' => false],
        'language' => ['type' => 'string', 'required' => false],
        'author' => ['type' => 'string', 'required' => false],
        'type' => ['type' => 'string', 'required' => false]
    ];

    protected $highlight_fields = [
        'content'
    ];

    public function __construct()
    {
        $fields = array_keys(static::$rules);
        $this->fields = array_merge($this->fields, $fields);

        $this->facets = array_merge($this->facets, [
            'theme',
            'subtheme',
            'date'
        ]);

        static::$rules = array_merge(static::$rules, parent::$rules);

        parent::__construct();
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);

        $query = "($query)";

        return parent::find($query, $sort);
    }
}
