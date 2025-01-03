<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot', [AuthController::class, 'forgotPassword']);

Route::prefix('guest')->middleware(['optional.token'])->group(function () {
    Route::get('/posts', [PostController::class, 'getPosts']);
    Route::get('/posts/{id}', [PostController::class, 'getDetail']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/authors/{id}', [UserController::class, 'getAuthorsById']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    try {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/check-token', [AuthController::class, 'checkToken']);
        Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
        // Add additional protected routes here
        Route::get('/current-user', [UserController::class, 'currentUser']);
        Route::post('/profile', [UserController::class, 'updateMyProfile']);
        // Media routes
        Route::get('/media', [MediaController::class, 'index']);
        Route::post('/media', [MediaController::class, 'store']);
        Route::post('/media/upload', [MediaController::class, 'upload']);
        Route::delete('/media/{media}', [MediaController::class, 'destroy']);
        Route::apiResource('packages', PackageController::class);
        Route::apiResource('posts', PostController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('tags', TagController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('transactions', TransactionController::class);
        Route::apiResource('settings', SettingController::class);
        Route::apiResource('bookmarks', BookmarkController::class);
        
        Route::post('/subscriptions', [TransactionController::class, 'buySubscriptions']);
        Route::post('/payment-sheet', [PaymentController::class, 'createPaymentSheet']);
        Route::post('/payment-sheet-googlepay', [PaymentController::class, 'createPaymentSheetGoogle']);
        Route::apiResource('comments', CommentController::class);
        

    } catch (\Exception $e) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
   
});
