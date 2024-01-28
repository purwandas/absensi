<?php

use App\Http\Controllers\ACL\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/', function () {
    return view('home');
})->name('home')->middleware('guest');

// FRONT PAGE & DASHBOARD
Route::group([
    'acl_group' => 'Pages',
    'middleware' => 'auth'
], function(){

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('admin/dashboard', function () {
        return view('admin-dashboard');
    })->name('admin.dashboard');
    
});

// NO ACCESS FRONT OR BACK
Route::get('/unaccessible', function () {
    return view('errors.422');
});

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

// PROFILE - LARAVEL BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
