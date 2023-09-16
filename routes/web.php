<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', function () {
    return view('home');
})->name('homepage');


Route::get('/home/index', 'StudentController@combineStudentData')->name('home');

Route::get('/home/create', 'StudentController@create')->name('create');

Route::post('/home/create/submit', 'StudentController@save')->name('submit');

Route::put('/home/update', 'StudentController@update')->name('update');

Route::delete('/home/delete', 'StudentController@delete')->name('delete');

