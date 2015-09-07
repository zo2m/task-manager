<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Rotas para manupulação de informações de clientes

Route::get('client', 'ClientController@index');
Route::post('client', 'ClientController@store');
Route::get('client/{id}', 'ClientController@show');
Route::delete('client/{id}', 'ClientController@destroy');
Route::put('client/{id}', 'ClientController@update');


//Rotas para manupulação de informações de usuários

Route::get('user', 'UserController@index');
Route::post('user', 'UserController@store');
Route::get('user/project', 'UserController@showAllUserWithProject');
Route::get('user/{id}', 'UserController@show');
Route::delete('user/{id}', 'UserController@destroy');
Route::put('user/{id}', 'UserController@update');
Route::get('user/{id}/member', 'UserController@member');

//Rotas para as anotações dos projetos

Route::get('project/notes', 'ProjectNoteController@index');
Route::post('project/note', 'ProjectNoteController@store');
Route::get('project/{id}/note/{noteId}', 'ProjectNoteController@show');
Route::get('project/{id}/notes', 'ProjectNoteController@showAllNotesFromProject');
Route::delete('project/{id}/note/{noteId}', 'ProjectNoteController@destroy');
Route::put('project/{id}/note/{noteId}', 'ProjectNoteController@update');


//Rotas para manipulação das informações dos projetos

Route::get('project', 'ProjectController@index');
Route::post('project', 'ProjectController@store');
Route::get('project/{id}', 'ProjectController@show');
Route::get('project/verify/{id}', 'ProjectController@verify');
Route::delete('project/{id}', 'ProjectController@destroy');
Route::put('project/{id}', 'ProjectController@update');
Route::get('project/{id}/members', 'ProjectController@members');
Route::post('project/members/add', 'ProjectController@addMembers');
Route::get('project/{projetc_id}/member/{member_id}', 'ProjectController@isMember');
Route::delete('project/{projetc_id}/member/{member_id}', 'ProjectController@removeMember');