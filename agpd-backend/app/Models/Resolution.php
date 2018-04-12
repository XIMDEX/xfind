<?php

namespace App\Http\Models;

use Xfind\models\Item;

class Resolution extends Item
{
    public function __construct()
    {
        $this->facets += [
            'filenamesh_s',
            'articuloinfringido_s',
            'grupoactividad_s',
            'provinciadenunciado_s',
            'tema_s',
            'filename_s',
            'numeroresolucion_s',
            'recurrida_s',
            'codigoprocedimiento_s',
            'tipoprocedimiento_s'
        ];

        parent::__construct();
    }
}
