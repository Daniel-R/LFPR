<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

//Route::get('home', function()
//{
//	return View::make('home.index');
//});
//Route::get('/', function()
//{
//	return View::make('home.test');
//});
//Route::any('home', function() {
//    return View::make('home.index');
//});

/*
 * Nutzbar für alle Controller wenn tests anstehen
 */
//Route::controller(Controller::detect());

Route::controller('home');
Route::get('index', 'home@index');
//Route::get('about', 'home@about');
//Route::get('test', 'home@test');

Route::controller('lfpr');
Route::get('login', 'lfpr@login');
Route::get('logout', 'lfpr@logout');
//Route::get('suche', 'lfpr@search');
Route::post('search', 'lfpr@search');
//Route::get('test', 'lfpr@test');

Route::group(array('before' => 'auth'), function() {
    Route::get('search', 'lfpr@search');
    Route::get('searchresult', 'lfpr@searchresult');
    Route::post('searchresult', 'lfpr@searchresult');
    Route::get('archiv', 'lfpr@archiv');
    Route::get('details', 'lfpr@details');
    Route::get('settings', 'lfpr@settings');
});

//Route::controller('test');
//Route::get('test', 'test@test');
//Route::post('ergebnis', 'test@ergebnis');

//Route::controller('benutzer');
//Route::get('authenticate', 'benutzer@authenticate');
//
//Route::controller('probe');
//Route::get('send', 'probe@send');

//session_start();
//$_SESSION['name'] = Input::get('userName');
//Route::get('empfang', 'probe@empfang', array($name = Input::get('userName'), $password = Input::get('password')));
//
//Route::get('search/(:any)/(:any)', 'lfpr@search');


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function() {
    return Response::error('404');
});

Event::listen('500', function() {
    return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

//Route::filter('before', function() {
//    if (Auth::guest()) {
//        return Redirect::to_route('lfpr.login');
//    }
//});

//Route::filter('after', function($response) {
//    if (Auth::guest()) {
//        return Redirect::to('login');
//    }
//});

//Route::filter('csrf', function() {
//    if (Request::forged()) {
//        return Response::error('500');
//    }
//});

Route::filter('auth', function() {
    if (Auth::guest()) {
        return Redirect::to('login');
    }
});

//Route::get('/', array('as' => 'profile', 'before' => 'auth', 'do' => function()
//{
//return View::make('account/profile');
//}));

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| List of the Controllers used within the Laravel Framework.
|
*/
//Route::controller('benutzer');
//Route::controller('in.a.sub.folder.account');
//Route::controller('mybundle::account');