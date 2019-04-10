<?php

namespace App\Http\Controllers;

use Xfind\Http\Controllers\api\ItemController;

class Controller extends ItemController
{
    public function index()
    {
        $data = parent::index();

        // Clean title from content
        foreach ($data['docs'] as &$doc) {
            $title = array_key_exists('name', $doc) ? trim($doc['name']) : '';
            if (isset($doc['content_flat']) && !is_null($doc['content_flat'])) {
                $content =  preg_replace('/\s+/', ' ', trim($doc['content_flat']));

                if (starts_with($content, ".")) {
                    $content = trim(substr($content, 1));
                }

                if (starts_with($content, $title)) {
                    $content = trim(substr($content, strlen($title)));
                }

                if (starts_with($content, 'Sección 1')) {
                    $content = trim(substr($content, strlen('Sección 1')));
                }

                if (starts_with($content, ".")) {
                    $content = trim(substr($content, 1));
                }

                $doc['content_flat'] = $content;
                $doc['content'] = $content;
            }
        }

        return $data;
    }
}
