<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Response\FractalResponse;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    /**
     * @var \App\Http\Response\FractalResponse
     */
    private $fractal;

    public function __construct(FractalResponse $fractal)
    {
        $this->fractal = $fractal;
        $this->fractal->parseIncludes();
    }

    /**
     * @param  array               $data
     * @param  TransformerAbstract $transformer [description]
     * @param  [type]              $resourceKey [description]
     * @return [type]                           [description]
     */
    public function item($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->item($data, $transformer, $resourceKey);
    }

    /**
     * @param  array               $data
     * @param  TransformerAbstract $transformer [description]
     * @param  [type]              $resourceKey [description]
     * @return [type]                           [description]
     */
    public function collection($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->collection($data, $transformer, $resourceKey);
    }
}
