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

Auth::routes([
    'reset' => false,
    'confirm' => false,
]);
Route::get('/', 'WordController@index');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('home', 'HomeController@index')->name('home');
Route::resource('results', 'ResultController');
Route::get('ranking', 'ResultController@showRanking')->name('ranking');
Route::get('users/{id}', 'ResultController@showRecord')->name('users');
Route::get('help', 'HomeController@showHelp')->name('help');
// Route::get('result/{id}', 'ResultController@showResult')->name('result');