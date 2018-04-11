<?php

namespace Xfind\models;

class News extends Item
{

    const TYPE = 'noticia';

    public function __construct()
    {
        $this->fields += [
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
        ];

        $this->facets += [
            'author',
            'content_flat',
            'content_render',
            'date',
            'name',
            'section',
            'state',
            'tags'
        ];

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
