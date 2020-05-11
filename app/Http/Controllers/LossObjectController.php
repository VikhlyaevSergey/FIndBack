<?php

namespace App\Http\Controllers;

use App\Http\Resources\LossObjectListResource;
use Illuminate\Http\Request;

class LossObjectController extends Controller
{
    public function getFavorites()
    {
        return new LossObjectListResource(user()->favoriteObjects()->paginate());
    }
}
