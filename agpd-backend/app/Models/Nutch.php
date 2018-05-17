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

        $query = "($query) AND content:*";

        return parent::find($query);
    }
}
