<?php

Route::group(['middleware' => ['web']], function () {

  Route::get('/', function () {
    return view('pages.home');
  });

  Route::resource('flyers', 'FlyersController');

});
