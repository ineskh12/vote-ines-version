<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', 'HomeController@index')->name('home.index');
Route::group(['middleware' => 'auth','prefix' => 'admin'], function () {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');

    Route::group(['prefix'=>'questions'], function(){
      Route::get('/', 'QuestionController@index')->name('questions.index');
      Route::post('datatable', 'QuestionController@datatable')->name('questions.datatable');
      Route::get('validate/{id}', 'QuestionController@validateQuestion')->name('questions.validate');
      Route::get('invalidate/{id}', 'QuestionController@invalidateQuestion')->name('questions.invalidate');

      Route::post('/{id}', 'QuestionController@destroy')->name('questions.delete');
    });

    Route::get('account', 'AdminController@account')->name('admin.account');
    Route::post('account', 'AdminController@account')->name('admin.account.submit');

    Route::group(['prefix'=>'resultats'], function(){
        Route::post('victorys', 'DashboardController@victorys')->name('dashboard.victorys');
        Route::get('show_chart', 'DashboardController@show_chart')->name('dashboard.show_chart');
        Route::get('resultat', 'DashboardController@resultat')->name('resultats.index');
    });

    Route::group(['middleware' => 'admin'], function () {
        Route::group(['prefix'=>'admins'], function(){
          Route::get('/', 'AdminController@index')->name('admins.index');
          Route::post('datatable', 'AdminController@datatable')->name('admins.datatable');
          Route::get('create', 'AdminController@create')->name('admins.create');
          Route::post('store', 'AdminController@store')->name('admins.store');
          Route::get('edit/{id}', 'AdminController@edit')->name('admins.edit')->where('id', '[0-9]+');
          Route::post('update/{id?}', 'AdminController@update')->name('admins.update')->where('id', '[0-9]+');
          Route::post('status', 'AdminController@status')->name('admins.status');
          Route::get('check_email', 'AdminController@check_email')->name('admins.check_email');
          Route::post('/{id}', 'AdminController@destroy')->name('admins.delete');
        });

        Route::group(['prefix'=>'events'], function(){
          Route::get('/', 'EventController@index')->name('events.index');
          Route::post('datatable', 'EventController@datatable')->name('events.datatable');
          Route::get('create', 'EventController@create')->name('events.create');
          Route::post('store', 'EventController@store')->name('events.store');
          Route::get('edit/{id}', 'EventController@edit')->name('events.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'EventController@update')->name('events.update')->where('id', '[0-9]+');
          Route::post('status', 'EventController@status')->name('events.status');
          Route::post('/{id}', 'EventController@destroy')->name('events.delete');
        });

        Route::group(['prefix'=>'judges'], function(){
          Route::get('/', 'JudgeController@index')->name('judges.index');
          Route::post('datatable', 'JudgeController@datatable')->name('judges.datatable');
          Route::get('create', 'JudgeController@create')->name('judges.create');
          Route::post('store', 'JudgeController@store')->name('judges.store');
          Route::get('edit/{id}', 'JudgeController@edit')->name('judges.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'JudgeController@update')->name('judges.update')->where('id', '[0-9]+');
          Route::post('status', 'JudgeController@status')->name('judges.status');
          Route::post('/{id}', 'JudgeController@destroy')->name('judges.remove');
        });

        Route::group(['prefix'=>'projects'], function(){
          Route::get('/', 'ProjectController@index')->name('projects.index');
          Route::post('datatable', 'ProjectController@datatable')->name('projects.datatable');
          Route::get('create', 'ProjectController@create')->name('projects.create');
          Route::post('store', 'ProjectController@store')->name('projects.store');
          Route::get('edit/{id}', 'ProjectController@edit')->name('projects.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'ProjectController@update')->name('projects.update')->where('id', '[0-9]+');
          Route::post('status', 'ProjectController@status')->name('projects.status');
          Route::post('/{id}', 'ProjectController@destroy')->name('projects.delete');
        });

        Route::group(['prefix'=>'categories'], function(){
          Route::get('/', 'CategoryController@index')->name('categories.index');
          Route::post('datatable', 'CategoryController@datatable')->name('categories.datatable');
          Route::get('create', 'CategoryController@create')->name('categories.create');
          Route::post('store', 'CategoryController@store')->name('categories.store');
          Route::get('edit/{id}', 'CategoryController@edit')->name('categories.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'CategoryController@update')->name('categories.update')->where('id', '[0-9]+');
          Route::post('status', 'CategoryController@status')->name('categories.status');
          Route::post('/{id}', 'CategoryController@destroy')->name('categories.delete');
        });

        Route::group(['prefix'=>'criterias'], function(){
          Route::get('/', 'CriteriaController@index')->name('criterias.index');
          Route::post('datatable', 'CriteriaController@datatable')->name('criterias.datatable');
          Route::get('create', 'CriteriaController@create')->name('criterias.create');
          Route::post('store', 'CriteriaController@store')->name('criterias.store');
          Route::get('edit/{id}', 'CriteriaController@edit')->name('criterias.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'CriteriaController@update')->name('criterias.update')->where('id', '[0-9]+');
          Route::post('status', 'CriteriaController@status')->name('criterias.status');
          Route::post('/{id}', 'CriteriaController@destroy')->name('criterias.delete');
        });

        Route::group(['prefix'=>'percentages'], function(){
          Route::get('/', 'PercentageController@index')->name('percentages.index');
          Route::post('datatable', 'PercentageController@datatable')->name('percentages.datatable');
          Route::get('create', 'PercentageController@create')->name('percentages.create');
          Route::post('store', 'PercentageController@store')->name('percentages.store');
          Route::get('edit/{id}', 'PercentageController@edit')->name('percentages.edit')->where('id', '[0-9]+');
          Route::post('update/{id}', 'PercentageController@update')->name('percentages.update')->where('id', '[0-9]+');
          Route::post('status', 'PercentageController@status')->name('percentages.status');
          Route::get('check_somme', 'PercentageController@check_somme')->name('percentages.check_somme');
          Route::post('/{id}', 'PercentageController@destroy')->name('percentages.delete');
        });

        Route::group(['prefix'=>'notes'], function(){
          Route::get('/', 'NoteController@multipleNoteBackOffice')->name('notes.multiple');
          Route::get('/{id}', 'NoteController@indexNoteBackOffice')->name('notes.backoffice')->where('id', '[0-9]+');
          Route::post('store', 'NoteController@storeNoteBackOffice')->name('notes.backoffice.store');
          Route::post('notebackoffice', 'NoteController@datatableNoteBackOffice')->name('notes.backoffice.datatable');
          //settings module
          Route::get('settings', 'NoteController@settings')->name('settings.note');
          Route::post('settings', 'NoteController@settings_submit')->name('settings.note.submit');
          Route::get('check_somme', 'NoteController@check_somme')->name('note.check_somme.note');
          Route::post('/{id}', 'NoteController@destroy')->name('percentages.delete');
        });
	});
});

//Auth::routes();

// web service
Route::group(['prefix' => 'api/v1'], function()
{
    Route::group(['prefix'=>'users'], function(){
        Route::post('loginQr', 'UserController@login_qr_ws')->name('users.login_qr_ws');
        Route::post('loginMail', 'UserController@login_mail_ws')->name('users.login_mail_ws');
        Route::post('vote', 'UserController@save_vote_ws')->name('users.save_vote_ws');

        Route::post('question', 'UserController@save_question')->name('users.save_question');
    });

    Route::get('events', 'EventController@getList')->name('events.get_list');
    Route::get('projects', 'ProjectController@getList')->name('projects.get_list');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
