<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseItemResource extends JsonResource
{
    public function toResponse($request)
    {
        return responseApi(parent::toResponse($request)->getData(true))->get();
    }
}
