<?php

namespace App\Models;

use Xfind\Models\Item;
use Illuminate\Support\Carbon;

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
        'author',
        'date',
        'tags',
        'language'
    ];

    protected $highlight_fields = [
        'content'
    ];

    protected static $facets = [
        'author',
        'tags',
        'date'
    ];

    protected $filterFields = [
        'author',
        'tags',
        'language',
        'date'
    ];


    public function addFilter(string $query, string $name = null)
    {
        if ($name === 'language') {
            $query = "-({$name}:[* TO *] OR -{$query})";
        }

        if ($name === 'date') {
            $dates = str_replace([
                'date:',
                '(',
                ')',
                '[',
                ']'
            ], '', $query);

            $dates = explode('TO', $dates);

            foreach ($dates as &$date) {
                $date = trim($date);
            }

            $dates[count($dates) - 1] = Carbon::parse($dates[count($dates) - 1])
                ->endOfMonth()
                ->format('Y-m-d\T00:00:00\Z');
            $query = 'date:([' . implode(' TO ', $dates) . '])';
        }
        return parent::addFilter($query, $name);
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);
        return parent::find($query, $sort);
    }
}
