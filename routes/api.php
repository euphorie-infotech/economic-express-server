<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
// Route::get('/category/{id}', [CategoryController::class, 'show']);

Route::group(['prefix' => '{admin}'], function () {
    // Route::post('/registration', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'loginUser']);
});
Route::group(['middleware'=>['auth:sanctum'],'prefix' => '{admin}'], function () {
    Route::post('/registration', [UserController::class, 'store']);
    //news
    Route::get('/news/', [NewsController::class, 'index']);
    Route::get('/news/{id}', [NewsController::class, 'show']);
    Route::post('/news/', [NewsController::class, 'store']);
    Route::put('/news/update/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    //Category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/show/{id}', [CategoryController::class, 'show']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    //Tag
    Route::get('/tag', [TagController::class, 'index']);
    Route::get('/tag/{id}', [TagController::class, 'show']);
    Route::post('/tag', [TagController::class, 'store']);
    Route::put('/tag/update/{id}', [TagController::class, 'update']);
    Route::delete('/tag/{id}', [TagController::class, 'destroy']);
    
    
    Route::post('/logout', [UserController::class, 'logout']);
});


Route::get('/', [PublicController::class, 'getActiveNews']);
Route::get('/{categoryName}', [PublicController::class, 'getActiveNewsByCategory']);
Route::get('/{tagName}', [PublicController::class, 'getActiveNewsByTag']);
Route::get('/{categoryName}/{id}', [PublicController::class, 'getNewsById']);
