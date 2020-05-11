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
        $favorites = user()->relationLoaded('favorite_objects') ? user()->favoriteObjects :
            user()->favoriteObjects()->select('objects.id')->get();

        return $this->collection->map(
            function ($item) use ($favorites) {
                $favorite = false;

                if ($favorites->where('id', $item->id)) {
                    $favorite = true;
                }

                $item->isFavorite = $favorite;

                return $item;
            })->toArray();
    }
}
