<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('/', function () {
    abort(404);
});

Route::prefix('log')->group(function () {
    Route::get('/', 'LogController@getLog');
    Route::post('/', 'LogController@setLog');
});

Route::prefix('about')->group(function () {
    Route::get('/', 'AboutController@getFaq');
});

Route::prefix('blog')->group(function () {
    Route::get('/', 'BlogController@getPosts');
    Route::get('/last', 'BlogController@getLastPost');
    Route::get('/categories', 'BlogController@getCategories');
    // Route::get('/import', 'BlogController@importOldPosts');
    Route::get('/{id}', 'BlogController@getPost');
    Route::get('/category/{id}', 'BlogController@getCategoryPosts');
    Route::get('/comments/{id}', 'BlogController@getComments');
    //
    Route::post('/comments/add', 'BlogController@addComments');
});

Route::prefix('users')->group(function () {
    Route::get('/', 'UserController@getUsers');
    Route::post('/search', 'UserController@searchUser');
    Route::get('/{id}', 'UserController@getUser');
});

Route::prefix('games')->group(function () {
    Route::get('/', 'GamesController@getGames');
    Route::post('/search', 'GamesController@searchGame');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', 'AuthController@loginUser')->name('login');
    Route::post('/register', 'AuthController@registerUser');
    Route::get('/register/twitch', 'AuthController@registerViaTwitch');
    Route::get('/register/twitch/code', 'AuthController@getTwitchCode')->name('registerTwitch');
    Route::get('/me', 'AuthController@me');
});

Route::prefix('overwatch')->group(function () {
    // Route::get('/tracker/medals', 'OverwatchTrackerController@getTrackerMedalsData');
    // Route::get('/tracker/trendsold', 'OverwatchTrackerController@getTrackerTrendsData');
    //
    Route::get('/tracker/ranked', 'Overwatch\RankedController@index');
    Route::get('/tracker/ranked/{id}', 'Overwatch\RankedController@profile');
    Route::get('/tracker/ranked/update/{id}', 'Overwatch\RankedController@update');
    Route::get('/tracker/statistics', 'Overwatch\StatisticsController@index');
    Route::get('/tracker/streamers', 'Overwatch\StreamersController@index');
    Route::get('/tracker/trends', 'Overwatch\TrendsController@index');
    Route::get('/tracker/medals', 'Overwatch\MedalsController@index');
});