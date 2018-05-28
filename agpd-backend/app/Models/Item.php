<?php

namespace App\Models;

use App\Core\Solr;
use Solarium\QueryType\Select\Query\Query;

class Item
{
    protected $limitPerPage = 20;
    protected $page = 0;
    protected $start = 0;
    protected $query = '*:*';
    protected $sort = [];

    protected static $rules = [
        'id' => ['type' => 'string', 'required' => true]
    ];

    protected $fields = [
        'id',
        'creation_date',
        'update_date',
    ];

    protected $highlight_fields = [];

    protected $facets = [];

    private $solarium;

    public function __construct()
    {
        $this->solarium = new Solr();
    }

    /**
     * Get the value of limitPerPage
     */
    public function getLimitPerPage()
    {
        return $this->limitPerPage;
    }

    /**
     * Set the value of limitPerPage
     *
     * @return  self
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @return  self
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the value of query
     *
     * @return  self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of facets
     */
    public function getFacets()
    {
        return $this->facets;
    }


    /**
     * Get the value of facets
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of start
     *
     * @return  self
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get the value of start
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the value of sort
     *
     * @return  self
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get the value of sort
     */
    public function getSort() : array
    {
        return $this->sort;
    }

    public function facets(array $facets = null)
    {
        if (is_null($facets)) {
            $facets = $this->facets;
        }
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }
        
        if (count($sort) === 0) {
            $sort = $this->getSort();
        }

        $this->solarium
            ->selectQuery($query)
            ->sort($sort);

        foreach ($this->facets as $facet) {
            $this->facetField($facet, $facet);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function highlight()
    {
        if (count($this->highlight_fields) > 0) {
            $hl = $this->solarium->getQuery()->getHighlighting();
            $hl->setFields($this->highlight_fields);
            $hl->setFragSize(160);
            $hl->setSimplePrefix('<b>');
            $hl->setSimplePostfix('</b>');
        }

        return $this;
    }

    public function one($query)
    {
        $result = $this->find($query)
            ->get();

        if (count($result) > 0 && isset($result['docs']) && count($result['docs']) > 0) {
            return $result['docs'][0];
        }

        return null;
    }

    public function get()
    {
        return $this->solarium->obtain();
    }

    public function createOrUpdate()
    {
        $update = $this->solarium->createUpdate();

        $doc = $update->createDocument();

        foreach ($this->fields as $field) {
            if (property_exists($this, $field)) {
                $doc->$field = $this->$field;
            }
        }

        $update->addDocument($doc);
        $update->addCommit();
        $result = $this->solarium->update($update);
        return $result->getResponse()->getStatusCode() == 200;
    }

    public function delete($id)
    {
        $delete = $this->solarium->createUpdate();
        $delete->addDeleteById($id);
        $delete->addCommit();
        return $this->solarium->update($delete);
    }

    public function ping()
    {
        return $this->solarium->test();
    }

    public function facetField($facet, $field)
    {
        $this->solarium->facetField($facet, $field);
        return $this;
    }

    public function limit(int $start = null, int $limit = null)
    {
        if (is_null($limit)) {
            $limit = $this->limitPerPage;
        }

        if (is_null($start)) {
            $start = $this->start;
        }

        $this->solarium->limit($limit, $start);
        return $this;
    }

    public function select(array $params)
    {
        $this->solarium->fields($params);
        return $this;
    }

    public function paginate($page = null)
    {
        if (is_null($page)) {
            $page = $this->page;
        }
        if ($page > 0) {
            $page -= 1;
        }


        $result = $this->solarium->limit($this->limitPerPage, $this->start + ($this->limitPerPage * $page))->obtain();

        $total = $result['numFound'];
        $pages = ceil($total / $this->limitPerPage);
        $page = $page + 1;
        $next = $page + 1;
        $prev = $page - 1;
        $pager = [
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
            'next' => ($next >= $pages) ? $pages : $next,
            'prev' => ($prev <= 0) ? 1 : $prev
        ];

        $result += ['pager' => $pager];

        return $result;
    }

    public function validate()
    {
        $valid = true;
        $errors = [];

        foreach (static::$rules as $property => $rule) {
            if ($rule['required'] && (!property_exists($this, $property) || empty($this->$property))) {
                array_push($errors, "$property is required");
                $valid = false;
            } elseif (property_exists($this, $property) && gettype($this->$property) !== $rule['type']) {
                array_push($errors, "$property has invalid type");
                $valid = false;
            }
        }

        return compact('valid', 'errors');
    }

    public function load($data)
    {
        foreach ($this->fields as $field) {
            if (isset($data[$field])) {
                $this->$field = $data[$field];
            }
        }

        return $this; // TODO check errors
    }
}
