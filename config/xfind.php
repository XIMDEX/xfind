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


return [
    'solr' => [
        'host' => env('SOLR_HOST', 'localhost'),
        'port' => intval(env('SOLR_PORT', 8983)),
        'path' => env('SOLR_PATH', '/solr/'),
        'core' => env('SOLR_CORE', 'demo'),
        'nutch' => env('SOLR_NUTCH', 'nutch'),
        'resolutions' => env('SOLR_RESOLUTIONS', 'resolutions'),
        'legalreports' => env('SOLR_LEGALREPORTS', 'legal_reports'),
    ]
];
