<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;

// Route::get('/', function () {
//     return view('index');
// });

Route::view('/' , 'index');

// 問い合わせフォーム
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'sendMail']);
Route::get('/contact/complete', [ContactController::class, 'complete'])->name('contact.complete');

Route::prefix('/admin')
  ->name('admin.')
  ->middleware('auth')
  ->group(function() {
    Route::middleware('auth')
      ->group(function() {
        // blog
        // Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
        // Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
        // Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
        // Route::get('/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('blogs.edit');
        // Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
        // Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');

        Route::resource('/blogs',[AdminBlogController::class])->except('show');

        //  ユーザー登録
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        // ログアウト
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
      });
    //ログインしていないときの処理
    Route::middleware('guest')
      ->group(function() {
          Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
          Route::post('/login', [AuthController::class, 'login']);
      });
  });

// blog
// Route::get('/admin/blogs', [AdminBlogController::class, 'index'])->name('admin.blogs.index')->middleware('auth');
// Route::get('/admin/blogs/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create');
// Route::post('/admin/blogs', [AdminBlogController::class, 'store'])->name('admin.blogs.store');
// Route::get('/admin/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit')->middleware('auth');
// Route::put('/admin/blogs/{blog}', [AdminBlogController::class, 'update'])->name('admin.blogs.update');
// Route::delete('/admin/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy');

//  ユーザー登録
// Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
// Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

// ユーザー認証
// Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
// Route::post('/admin/login', [AuthController::class, 'login']);
//Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');