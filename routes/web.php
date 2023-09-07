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


Route::get('/home', 'StudentController@combineStudentData')->name('home');

Route::get('/home/create', 'StudentController@create')->name('create');
Route::post('/home/create/submit', 'StudentController@save')->name('submit');


Route::get('/home/edit/{student_type}/{id}', 'StudentController@displayEdit')->name('edit');
Route::put('/home/update', 'StudentController@update')->name('update');
Route::post('/home', 'StudentController@filter')->name('filter.students');
Route::delete('/home/delete/{id}', 'StudentController@delete')->name('delete.student');

