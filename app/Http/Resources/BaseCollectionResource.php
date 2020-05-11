<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollectionResource extends ResourceCollection
{
    public function toResponse($request)
    {
        return responseApi(parent::toResponse($request)->getData(true))->get();
    }
}
