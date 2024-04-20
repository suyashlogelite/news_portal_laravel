<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;


  
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

// Auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard']); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Admin/Categories Routes
Route::get('categories', [AdminController::class, 'categories'])->name('categories');
Route::post('categories', [AdminController::class, 'addCategory'])->name('categories.add');
Route::get('edit-category/{id}', [AdminController::class, 'editCategory'])->name('categories.edit');
Route::get('delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
Route::get('status-category/{id}', [AdminController::class, 'statusCategory'])->name('categories.update');

// Admin/Tags Routes
Route::get('tags', [AdminController::class, 'tags'])->name('tags');
Route::post('tags', [AdminController::class, 'addTags'])->name('tags.add');
Route::get('edit-tag/{id}', [AdminController::class, 'editTags'])->name('tags.edit');
Route::get('delete-tag/{id}', [AdminController::class, 'deleteTag'])->name('tags.delete');
Route::get('status-tag/{id}', [AdminController::class, 'statusTag'])->name('tags.status');

// Admin/Users Routes
Route::get('users', [AdminController::class, 'users'])->name('users');
Route::post('users', [AdminController::class, 'addUser'])->name('users.add');
Route::get('edit-user/{id}', [AdminController::class, 'editUser'])->name('users.edit');
Route::get('delete-user/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

// Admin/News Routes
Route::get('news', [AdminController::class, 'news'])->name('news');
Route::get('addNewsForm', [AdminController::class, 'addNewsForm'])->name('news.add');
Route::post('addNewsForm', [AdminController::class, 'addNewsPost'])->name('post.add');
Route::post('uploadImage', [AdminController::class, 'uploadImage'])->name('ckeditor.upload');
Route::get('status-news/{id}', [AdminController::class, 'statusNews'])->name('news.status');
Route::get('editNewsForm', [AdminController::class, 'editNewsForm'])->name('news.edit');
Route::get('delete-post/{id}', [AdminController::class, 'deletePost'])->name('post.delete');
Route::get('viewPost', [AdminController::class, 'viewPost'])->name('post.view');








