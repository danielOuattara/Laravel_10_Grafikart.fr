<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blog', function () {
    return "<h1>Hello and welcome to our blog</h1>";
});

// http://127.0.0.1:8000/array?name=john&age=45
Route::get('/array', function (Request $request) {
    return [
        "article_1" => "Article_1 content",
        "article_2" => "Article_2 content",
        "name" => $_GET["name"] ?? "No Name provided",
        "path" => $request->path(),
        "url" => $request->url(),
        "all" => $request->all(),
        "all params" => $request->input(),
        "username" => $request->input("name", "John Doe"),
    ];
});


// http://127.0.0.1:8000/blog/php-laravel-23?name=John%20Doe
Route::get('/blog/{slug}-{id}', function (string $slug, string $id, Request $request) {
    return [
        "slug" => $slug,
        "id" => $id,
        "name" => $request->input("name") ?? "No Name provided"
    ];;
})->where([
    "id" => "[0-9]+",
    "slug" => "[a-z0-9\-]+"
]);
