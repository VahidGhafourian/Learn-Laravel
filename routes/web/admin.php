<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});

Route::resource('users', \App\Http\Controllers\Admin\User\UserController::class);
Route::get('/users/{user}/permissions', [\App\Http\Controllers\Admin\User\PermissionController::class, 'create'])
    ->name('users.permissions')->middleware('can:staff-user-permissions');
Route::post('/users/{user}/permissions', [\App\Http\Controllers\Admin\User\PermissionController::class, 'store'])
    ->name('users.permissions.store')->middleware('can:staff-user-permissions');
Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
Route::post('attribute/values', [\App\Http\Controllers\Admin\AttributeController::class, 'getValues']);

Route::get('comments/unapproved', [\App\Http\Controllers\Admin\CommentConroller::class, 'unapproved'])->name('comments.unapproved');
Route::resource('comments', \App\Http\Controllers\Admin\CommentConroller::class)->only(['index', 'update', 'destroy']);

Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
