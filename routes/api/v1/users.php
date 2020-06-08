<?php

Route::middleware('auth:api')->group(function() {
   Route::get('/profile', 'UserController@getProfile');
   Route::put('/profile', 'UserController@updateProfile');
   Route::post('/logout', 'UserController@logout');

   Route::delete('/phones', 'UserController@deletePhone');
   Route::delete('/emails', 'UserController@deleteEmail');
   Route::delete('/places', 'UserController@deletePlace');
});
