<?php

namespace App\Http\Controllers;

use App\Http\Resources\LossObjectListResource;

class LossObjectController extends Controller
{
    /**
     * список избранных объявлений
     *
     * @return LossObjectListResource
     */
    public function getFavorites()
    {
        return new LossObjectListResource(user()->favoriteObjects()->paginate());
    }

    /**
     * список объявлений юзера
     *
     * @return LossObjectListResource
     */
    public function getMy()
    {
        return new LossObjectListResource(user()->objects()->paginate());
    }
}
