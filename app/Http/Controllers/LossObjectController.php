<?php

namespace App\Http\Controllers;

use App\Http\Resources\LossObjectListResource;

class LossObjectController extends Controller
{
    public function getFavorites()
    {
        return new LossObjectListResource(user()->favoriteObjects()->paginate());
    }
}
