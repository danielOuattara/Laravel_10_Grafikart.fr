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

Route::prefix('blog')->name("blog.")->group(function () {
    Route::get('/', function (Request $request) {
        # http://localhost:8000/blog?name=john_doe&age=41
        # OR
        # http://localhost:8000/blog?age=41

        // return "Bonjour";
        // echo $request;
        return [
            "name" => "John Doe",
            "path" => $request->path(),
            "url" => $request->url(),
            "all" => $request->all(),
            "input" => $request->input("name", "Mike Tyson"),
            "link" => \route("blog.show", ["id" => 07, "slug" => "article-php"])
        ];
    })->name("index");

    Route::get('/{slug}-{id}', function (string $slug, string $id, Request $request) {
        # http://localhost:8000/blog/mon-premier-article-09
        # OR
        # http://localhost:8000/blog/mon-premier-article-09?name=john_doe&age=41
        // return "Bonjour Chef !";
        return [
            "slug" => $slug,
            "id" => $id,
            "name" => $request->input("name", "Mike Tyson"),
            "age" => $request->input("age", "20"),
        ];
    })->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+',
    ])->name("show");
});
# php artisan route:list