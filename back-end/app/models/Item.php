<?php

namespace Xfind\models;

use core\Solr;

class Item
{
    protected $facets = [
        'id',
        'tipodocumento_s',
        'fechafirma_rdt',
        'attr_contentfile'
    ];

    private $solarium;

    public function __construct()
    {
        $this->solarium = new Solr();
    }

    public function getFacets()
    {
        return $this->facets;
    }

    public function find($query = '*:*')
    {
        return $this->solarium
                    ->selectQuery($query);
    }

    public function ping()
    {
        return $this->solarium->test();
    }
}
