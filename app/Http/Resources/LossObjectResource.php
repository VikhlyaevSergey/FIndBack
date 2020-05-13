<?php

namespace App\Http\Resources;


class LossObjectResource extends BaseItemResource
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
        return [
            'id'           => $this->id,
            'publishDate'  => $this->created_at,
            'previewImage' => $this->image_url,
            'isFavourite'  => user()->favoriteObjects->firstWhere('id', $this->id) ? true : false,
            'objectType'   => $this->type,
            'petType'      => $this->pet_type,
            'name'         => $this->name,
            'info'         => $this->description,
            'regime'       => $this->regime,
        ];
    }
}
