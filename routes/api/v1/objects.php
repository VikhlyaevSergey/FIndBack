<?php

Route::middleware('auth:api')->group(function() {
    Route::get('/favorites', 'LossObjectController@getFavorites');
});
