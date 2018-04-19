<?php

namespace App\Http\Models;

use App\Core\Solr;

class Item
{
    protected $limitPerPage = 20;
    protected $page = 0;
    protected $start = 0;
    protected $query = '*:*';

    protected $fields = [
        'id',
        'creation_date',
        'update_date',
    ];

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

    public function facets(array $facets = null)
    {
        if (is_null($facets)) {
            $facets = $this->facets;
        }
    }

    public function find($query = null)
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $this->solarium
            ->selectQuery($query);

        foreach ($this->facets as $facet) {
            $this->facetField($facet, $facet);
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

    public function createOrUpdate($data)
    {
        $update = $this->solarium->createUpdate();

        $doc = $update->createDocument();

        $data = array_filter($data, function ($val) {
            return in_array($val, self::getFields());
        }, ARRAY_FILTER_USE_KEY);

        foreach ($data as $key => $value) {
            $doc->$key = $value;
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
        if ($page > 0)
            $page -= 1;


        $result = $this->solarium->limit($this->limitPerPage, $this->start + ($this->limitPerPage * $page))->obtain();

        $total = $result['numFound'];
        $pages = ceil($total / $this->limitPerPage);
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
}
