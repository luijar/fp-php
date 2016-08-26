<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/main',    'Main');
Route::post('/new',    'Main@newItem');
Route::post('/delete', 'Main@deleteItems')->middleware(App\Http\Middleware\NullableParams::class. ':items');