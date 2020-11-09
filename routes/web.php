<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('examples', function() {
    return view('examples', [
        'posts' => Post::all(),
    ]);
});

Route::get('/post/{post}', function (Post $post) {
    return view('posts.show', [
        'post' => $post,
    ]);
})->name('posts.show');

Route::post('/post/{post}/comment', function (Request $request, Post $post) {
    $request->validate([
        'comment' => 'required|min:4'
    ]);

    Comment::create([
        'post_id' => $post->id,
        'username' => 'Guest',
        'content' => $request->comment,
    ]);

    return back()->with('success_message', 'Comment was posted!');
})->name('comment.store');
