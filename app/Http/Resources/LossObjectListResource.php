<?php

namespace App\Http\Resources;


class LossObjectListResource extends BaseCollectionResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(
            function ($item) {
                return new LossObjectResource($item);
            })->toArray();
    }
}
