<?php

namespace App\Http\Models;

class News extends Item
{
    const TYPE = 'noticia';

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
        ]);

        $this->facets = array_merge($this->facets, [
            'author',
            'date',
            'section',
            'state',
            'tags'
        ]);

        parent::__construct();
    }

    public function find($query = null)
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $query = "($query) AND type:" . static::TYPE;

        return parent::find($query);
    }
}
