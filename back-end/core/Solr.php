<?php

namespace core;

use core\Config;
use Solarium\Core\Client\Endpoint;
use Solarium\Core\Query\QueryInterface;
use Solarium\Core\Query\Result\ResultInterface;
use Solarium\Exception as SolrException;
use Solarium\Core\Client\Client as SolrClient;

class Solr extends SolrClient
{
    public static $json_response = 'json';
    public static $default_response = 'default';

    private $responseType;
    private $conf;
    private $solarium;
    private $query;
    private $type;

    public function __construct()
    {
        $conf = Config::getGroup('SOLR');
        $this->conf = [
            'endpoint' => [
                'solr' => [
                    'host' => $conf['solr_host'],
                    'port' => intval($conf['solr_port']),
                    'path' => $conf['solr_path'],
                    'core' => $conf['solr_core']
                ]
            ]
        ];

        $this->responseType = static::$default_response;
        parent::__construct($this->conf);
    }

    /**
     * Set the value of query
     *
     * @param $query
     */
    public function setQuery($query)
    {
        $this->query = $this->createQuery($query);
        return $this;
    }

    /**
     * Get the value of query
     */
    public function getQuery()
    {
        return $this->query;
    }


    public function test()
    {
        $ping = $this->createPing();

        try {
            $result = $this->obtain($ping);
            return 'Successful connection with solr';
        } catch (SolrException $e) {
            return $e;
        }
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get the value of responseType
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * Set the value of responseType
     *
     * @return  self
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
        return $this;
    }

    /**
     * Execute a query.
     *
     * @param QueryInterface $query
     * @param Endpoint|string|null $endpoint
     *
     * @return ResultInterface
     */
    public function obtain(QueryInterface $query = null, $endpoint = null)
    {
        if (!is_null($query)) {
            $this->query = $query;
        }

        $result = $this->execute($this->query, $endpoint);

        $data = $result->getResponse()->getBody();
        $facets = $result->getFacetSet() ? $result->getFacetSet()->getFacets() : [];
        $resultFacet = [];
        foreach ($facets as $facet => $value) {
            $resultFacet[$facet] = $value->getValue();
        }

        $response = json_decode($data, true);
        
        if (array_key_exists('response', $response)) {
            $response = $response['response'];
        }
        $response['facets'] = $resultFacet;

        return $response;
    }

    public function selectQuery(string $queryArgs = '*:*')
    {
        $query = $this->createSelect();
        $query->setQuery($queryArgs);
        $this->query = $query;
        return $this;
    }

    public function limit(int $limit, int $start = 0)
    {
        if ($start > 0) {
            $this->query->setStart($start);
        }
        $this->query->setRows($limit);

        return $this;
    }

    public function fields(array $fields)
    {
        $this->query->setFields($fields);
        return $this;
    }

    public function facet($facet, $options)
    {
        $this->query->getFacetSet()
            ->createFacetQuery($facet)
            ->setQuery($options);
        return $this;
    }
}
