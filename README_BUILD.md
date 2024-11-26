# README BUILD

## 01 - info general

## 02 - introduction

## 03 - structure dossier project application laravel

- create  a new Laravel v10 application

```bash
composer create-project laravel/laravel=10 _03_structure_dossier_appli_laravel
```

- `app/` contains most of the application core logic
- `composer.json` for Namespaces registry
- `boostrap/` for bootstrapping a Laravel app
- `config/` which have the main application settings
- `database/` where to handle all databases task
- `public/` as root folder for publicly accessible items in the application
- `resources/` for JS and CSS files
- `routes/`
- `storage/` as stock zone
- `test/`
- `vendor/` which contains all the packages to run the application
- `.env` file, CAUTION do not version this file, add to .gitignore
- `.artisan` file useful to execute commands, like start the application server `more traditionally`

```bash
php -S localhost:8000 -t public
```

or using `artisan`

```bash
php artisan # to see all available command from `artisan`    
# then
php artisan serve
```

## 04 - routing

- show all the routes in the project

```sh
php artisan route:list
```

## 05 - ORM Eloquent

- update `.env` file

```bash
# .env file

DB_CONNECTION=sqlite
```

- create the first migration file for posts table

```bash
php artisan make:migration create_posts_table
```

- update the function in `database/migrations/...create_posts_table/up`
  
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

- then make a migration to add posts columns to the database

```bash
php artisan migrate
```

- then create the Model

```bash
php artisan make:model Post
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
}
```

- using `manuel` database post creation & `dd()`

```php

Route::prefix('/blog')->name("blog.")->group(function () {
    Route::get('/', function (Request $request) {
        # http://localhost:8000/blog?name=john_doe&age=41
        # OR
        # http://localhost:8000/blog?age=41

        // return "Bonjour";
        // echo $request;


        #  1. create a post:
        // $post = new Post();
        // $post->title = "Mon second post";
        // $post->slug = "mon-second-post";
        // $post->content = "Mon second post content";
        // $post->save();


        # 2. all posts
        // $all_posts = Post::all();
        // dd($all_posts);


        # 3. find the first post
        // $posts = Post::all();
        // dd($posts->first());


        # 4. find one post
        // $post_1 = Post::find(1);
        // dd($post_1->title);


        # 5. find or fail
        // $post_1 = Post::findOrFail(3);
        // dd($post_1);


        # 6. pagination
        // $post = Post::paginate(1);
        $post = Post::paginate(1, ['id', 'title']);
        // dd($post);



        return [
            "name" => "John Doe",
            "path" => $request->path(),
            "url" => $request->url(),
            "all" => $request->all(),
            "input" => $request->input("name", "Mike Tyson"),
            "link" => \route("blog.show", ["id" => 07, "slug" => "article-php"]),
            "post" => $post,
            "posts" => Post::all(),
            "posts_id_and_slug" => Post::all(['id', 'slug'])
        ];
    })->name("index");
```

- Laravel Doc about Query Builder <https://laravel.com/docs/10.x/queries> : to design specific queries from Models

- update a post on the database
  
  - 1: get the post
  - 2: update it

```php
# /routes/web.php

        # 7. using Query builders to find post with positive 'id'
        $posts = Post::where('id', '>', 0)->get();
        // OR
        $posts = Post::where('id', '>', 1)->get();
        // OR
        $posts = Post::where('id', '>', 1)->limit(1)->get();
        // dd($posts);

        # 8. Get a post and update it
        $post = Post::find(2);
        $post->title = "Nouveau Titre";
        $post->save();

        # 9. Get a post and delete it
        $post = new Post();
        $post->title = "my-post-to-delete";
        $post->slug = "my-post-to-delete";
        $post->content = "my-post-to-delete";
        $post->save();

        $post = Post::find(5);
        $post->delete();

        # 10. create()
        $post = Post::create([
            $post->title = "Created Post",
            $post->slug = "created-post",
            $post->content = "Content of The created post",
        ]);
        dd($post);
```

- routing in casse of not found

```php
    Route::get('/{slug}-{id}', function (string $slug, string $id, Request $request) {
        # http://localhost:8000/blog/mon-premier-article-09
        # OR
        # http://localhost:8000/blog/mon-premier-article-09?name=john_doe&age=41
        // return "Bonjour Chef !";



        $post = Post::findOrFail($id);

        if ($post->slug !== $slug) {
            return to_route('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }
    })->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+',
    ])->name("show");
```

## 06 Controllers

- output logic from `routes` to separate file

- create a blog controller

```sh
php artisan make:controller PostController
```

- output:

```php


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    //
}
```

- update the PostController
  
```php
# /app/Http/Controllers/PostController.php

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function blog(): Paginator
    {
        return Post::paginate(25);
    }

    public function show(string $slug, string $id, Request $request): RedirectResponse | Post
    {
        $post = Post::findOrFail($id);
        if ($post->slug !== $slug) {
            return to_route('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }
    }
}
```

- update

```php
# /routes/web.php

<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
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

Route::prefix('/blog')->name("blog.")->controller(PostController::class)->group(function () {
    Route::get('/', 'blog')->name("index");

    Route::get('/{slug}-{id}', 'show')->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+',
    ])->name("show");
});


# php artisan route:list
```
