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

Route::get('/', function ()
{
    return view('welcome');
});


Route::post('oauth/access_token', function()
{
    return Response::json(Authorizer::issueAccessToken());
});


//Protege todas as rotas. Só haverá acesso quando o usuário estiver logado no sistema
Route::group(['middleware' => 'oauth'], function()
{


    //Rotas para manupulação de informações de clientes. Pega todos os métodos listados no controller
    Route::resource('client', 'ClientController', ['except' => ['create', 'edit']]);



    //Rotas para manupulação de informações de usuários
    Route::get('user/project', 'UserController@showAllUserWithProject');
    Route::get('user/{id}/member', 'UserController@member');
    Route::resource('user', 'UserController', ['except' => ['create', 'edit']]);
    /*Route::group(['prefix' => 'user'], function()
    {
        Route::get('{id}', 'UserController@show');
        Route::get('{id}/member', 'UserController@member');
        Route::delete('{id}', 'UserController@destroy');
        Route::put('{id}', 'UserController@update');
        Route::post('user', 'UserController@store');
    });*/

    //agrupa as rotas de acordo com o prefixo project
    Route::get('project/{id}/members', 'ProjectController@members');
    Route::get('project/{id}/ismember', 'ProjectController@isMember');
    Route::post('project/addmembers', 'ProjectController@addMembers');
    Route::resource('project', 'ProjectController', ['except' => ['create', 'edit']]);

    Route::group(['prefix' => 'project'], function()
    {

        //Rotas para as anotações dos projetos
        Route::get('notes', 'ProjectNoteController@index');
        Route::post('note', 'ProjectNoteController@store');
        Route::get('{id}/note/{noteId}', 'ProjectNoteController@show');
        Route::get('{id}/notes', 'ProjectNoteController@showAllNotesFromProject');
        Route::delete('{id}/note/{noteId}', 'ProjectNoteController@destroy');
        Route::put('{id}/note/{noteId}', 'ProjectNoteController@update');

        Route::post('{id}/file', 'ProjectFileController@store');
        Route::delete('{id}/file/{fileid}', 'ProjectFileController@apagar');

    });


});

