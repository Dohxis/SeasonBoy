<?php

Route::get('/', 'ConnectController@index');
Route::post('/', 'ConnectController@handle');

Route::get('/play', 'PlayController@index');
Route::get('/logout', 'PlayController@logout');
