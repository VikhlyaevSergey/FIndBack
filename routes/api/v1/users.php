<?php

Route::middleware('auth:api')->group(function() {
   Route::get('/profile', 'UserController@getProfile');
});
