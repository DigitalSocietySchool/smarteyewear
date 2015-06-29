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


Route::group(['middleware' => ['auth']], function() {
  	Route::get('/', 'HomeController@index');

  	Route::get('/todo', 'RandomlistController@todo');

  	Route::get('/meetlocaties', 'LocationController@manage');

  	Route::get('/locations_subcategory/show', 'LocationController@showSubcatLocations');

	Route::get('/randomlist/', 'RandomlistController@generateListsByArea');

	Route::get('/newChecklist/', 'ChecklistController@getNewChecklist');

	Route::get('/checklist/', 'ChecklistController@index');

	Route::get('/randomlist/updated', 'RandomlistController@updated');

	Route::get('/generateDemoItems', 'ChecklistController@generateDemoItems');

	Route::get('/checklistproblems', 'ChecklistController@getProblems');
});

Route::get('home', 'HomeController@index');

Route::get('/location/', 'LocationController@index');
Route::post('/location/', 'LocationController@create');
Route::get('/location/{id}', 'LocationController@show');
Route::put('/location/{id}', 'LocationController@update');
Route::delete('/location/{id}', 'LocationController@destroy');

Route::post('/checklist/', 'ChecklistController@create');
Route::get('/checklist/{id}', 'ChecklistController@show');
Route::put('/checklist/{id}', 'ChecklistController@update');
Route::delete('/checklist/{id}', 'ChecklistController@destroy');

//Route::get('/checklist_demo/', 'ChecklistController@insertDemoData');


Route::get('/subcategories/grades/{id}', 'LocationController@getGrades');

Route::get('/location/name/{id}', 'LocationController@getLocationByName');
Route::get('/location/subcategories/{id}', 'LocationController@getSubcategoryLocationByName');
Route::get('/locations/search', 'LocationController@searchLocationByName');
Route::get('/locations/all', 'LocationController@getAllLocations');
Route::get('/location/categories/{id}', 'LocationController@getLocationCategories');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
