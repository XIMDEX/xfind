<?php

namespace App\Models;

class Nutch extends Item
{
    protected $fields = [
        'id',
        'tstamp',
        'anchor',
        'title',
        'url',
        'content'
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected $facets = [
        'author',
        'date',
        //'type',
        'tags'
    ];

    public function find($query = null)
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $query = (strpos('content:', $query) != -1) ? $query : "($query) AND content:*";

        return parent::find($query);
    }
}
