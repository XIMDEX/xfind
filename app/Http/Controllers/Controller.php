<?php
/**
 * Copyright (C) 2019 Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


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
