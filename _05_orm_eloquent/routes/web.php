<?php

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

Route::prefix('/blog')->name("blog.")->group(function () {
    Route::get('/', function (Request $request) {
        # http://localhost:8000/blog?name=john_doe&age=41
        # OR
        # http://localhost:8000/blog?age=41

        // return "Bonjour";
        // echo $request;

        // #  1. create a post:
        // $post = new Post();
        // $post->title = "Mon second post";
        // $post->slug = "my-other-post";
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

        # 7. using Query builders to find post with positive 'id'
        // $posts = Post::where('id', '>', 0)->get();
        // OR
        // $posts = Post::where('id', '>', 1)->get();
        // OR
        $posts = Post::where('id', '>', 1)->limit(1)->get();
        // dd($posts);

        # 8. Get a post and update it
        // $post = Post::find(2);
        // $post->title = "Nouveau Titre";
        // $post->save();

        # 9. Get a post and delete it
        // $post = new Post();
        // $post->title = "my-post-to-delete";
        // $post->slug = "my-post-to-delete";
        // $post->content = "my-post-to-delete";
        // $post->save();

        // $post = Post::find(5);
        // $post->delete();

        # 10. create()
        // $post = Post::create([
        //     $post->title = "Created Post",
        //     $post->slug = "created-post",
        //     $post->content = "Content of The created post",
        // ]);
        // dd($post);

        # 11. 
        $posts = Post::paginate(25);


        return [
            // "name" => "John Doe",
            // "path" => $request->path(),
            // "url" => $request->url(),
            // "all" => $request->all(),
            // "input" => $request->input("name", "Mike Tyson"),
            // "link" => \route("blog.show", ["id" => 07, "slug" => "article-php"]),
            // "post" => $post,
            // "posts" => Post::all(),
            "posts" => $posts,
            // "posts_id_and_slug" => Post::all(['id', 'slug'])
        ];
    })->name("index");

    Route::get('/{slug}-{id}', function (string $slug, string $id, Request $request) {
        # http://localhost:8000/blog/mon-premier-article-09
        # OR
        # http://localhost:8000/blog/mon-premier-article-09?name=john_doe&age=41
        // return "Bonjour Chef !";


        // return [
        //     "slug" => $slug,
        //     "id" => $id,
        //     "name" => $request->input("name", "Mike Tyson"),
        //     "age" => $request->input("age", "20"),
        // ];

        return $post;

        $post = Post::findOrFail($id);

        if ($post->slug !== $slug) {
            return to_route('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }
    })->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+',
    ])->name("show");
});


# php artisan route:list