<?php

Route::get('/', 'ConnectController@index');
Route::post('/', 'ConnectController@handle');

Route::get('/play', 'PlayController@index');
Route::get('/logout', 'PlayController@logout');

Route::get('/go/{id}', 'PlayController@control');

Route::get('/next', 'PlayController@next');

Route::get('/stats', 'PlayController@stats');

Route::get('/startTutorial', 'PlayController@tut');
Route::get('/endTutorial', 'PlayController@endtut');
