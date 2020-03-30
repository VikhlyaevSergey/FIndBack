<?php

Route::middleware('auth:api')->group(function() {
   Route::get('/profile', 'UserController@getProfile');
   Route::put('/profile', 'UserController@updateProfile');
});
