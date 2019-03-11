<?php

namespace App\Models;

use Xfind\Models\Item;

class LegalReport extends Item
{
    protected $facetSort = 'count';

    public static $rules = [
        'name' => ['type' => 'string', 'required' => true],
        'slug' => ['type' => 'string', 'required' => true],
        'section' => ['type' => 'string', 'required' => true],
        'creation_date' => ['type' => 'string', 'required' => true],
        'update_date' => ['type' => 'string', 'required' => true],

        'state' => ['type' => 'string', 'required' => true, 'values' => ['publish']],
        'content_flat' => ['type' => 'string', 'required' => false],
        'content' => ['type' => 'string', 'required' => false],
        'pdf_content' => ['type' => 'string', 'required' => false],

        'title' => ['type' => 'string', 'required' => false],
        'pages' => ['type' => 'string', 'required' => false],

        'date' => ['type' => 'string', 'required' => true],
        'year' => ['type' => 'string', 'required' => true],
        
        'code' => ['type' => 'string', 'required' => false],
        'theme' => ['type' => 'string', 'required' => false],
        'subtheme' => ['type' => 'string', 'required' => false],
        'administration' => ['type' => 'string', 'required' => false],
        'normative_range' => ['type' => 'string', 'required' => false],
        'norm_type' => ['type' => 'string', 'required' => false],
        'language' => ['type' => 'string', 'required' => false],
        'author' => ['type' => 'string', 'required' => false],
        'type' => ['type' => 'string', 'required' => false]
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected $filterFields = [
        'language',
        'author',
        'norm_type',
        'normative_range',
        'administration',
        'subtheme',
        'theme',
        'code',
        'type',
        'date',
        'year'
    ];

    private $legalTypes = [
        'informes_historicos' => 'historicalFacets',
        'informes_preceptivos' => 'mandatoryFacets'
    ];

    private $historicalFacets = [
        'theme',
        'year',
    ];

    private $mandatoryFacets = [
        'administration',
        'normative_range',
        'year'
    ];

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $filters = $this->getFilters();
        $type = '';
        foreach ($filters as $filter) {
            $tmp = explode(':', $filter);
            if ($tmp[0] === 'type') {
                $type = $this->legalTypes[trim($tmp[1], '()')] ?? null;
                if (is_null($type)) {
                    continue;
                }
                static::$facets = array_merge(static::$facets, $this->$type);
                break;
            }
        }

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);
        return parent::find($query, $sort);
    }
}
