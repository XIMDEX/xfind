<?php

namespace Xfind\controllers;

use Xfind\controllers\ItemController;

class resolutionController extends ItemController
{
    protected $className = '\Xfind\models\Resolution';

    public function index()
    {
        $params = $this->getQueryParams();
        $data = ['facets' => $this->getFacets()] +
                $this->model->find()
                    ->select([
                        'filenamesh_s',
                        'articuloinfringido_s',
                        'grupoactividad_s',
                        'provinciadenunciado_s'
                    ])
                    ->facet('2012', 'fechafirma_rdt:"2012"')
                    ->paginate();
        
        $result = $this->response->withHeader(
            'Content-Type',
            'application/json'
        )->write($this->setResponse($data, true));

        return $result;
    }
}
