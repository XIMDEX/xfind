<?php

namespace App\Models;

use Xfind\Models\Item;

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
        'language'
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected static $facets = [
        'author',
        'date',
        'tags'
    ];

    protected $filterFields = [
        'author',
        'date',
        'tags',
        'language'
    ];


    public function addFilter(string $query, string $name = null)
    {
        if ($name === 'language') {
            $query = "-({$name}:[* TO *] OR -{$query})";
        }
        return parent::addFilter($query, $name);
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);

        $query = (strpos('content:', $query) != -1) ? $query : "($query) AND content:*";

        return parent::find($query, $sort);
    }
}
