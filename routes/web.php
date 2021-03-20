<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\OrganizationsController;
use \App\Http\Controllers\UsersController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum','verified'])->group(function (){
    Route::get('/organizations',[OrganizationsController::class,'index'])->name('organizations.index');
    Route::get('/organizations/register',[OrganizationsController::class,'registPage'])->name('organizations.register');
    Route::post('/organizations/submit',[OrganizationsController::class,'saveData'])->name('organizations.submit');
    Route::get('/organizations/edit/{id}',[OrganizationsController::class,'editPage'])->name('organizations.edit');
    Route::get('/organizations/delete/{id}',[OrganizationsController::class,'deleteData'])->name('organizations.delete');
    Route::post('/organizations/edit/{id}/save',[OrganizationsController::class,'editData'])->name('organizations.edit.submit');

    Route::get('/pic',[UsersController::class,'index'])->name('pic.index');
    Route::get('/pic/register',[UsersController::class,'registPage'])->name('pic.register');
    Route::post('/pic/submit',[UsersController::class,'saveData'])->name('pic.submit');
    Route::get('/pic/edit/{id}',[UsersController::class,'editPage'])->name('pic.edit');
    Route::get('/pic/delete/{id}',[UsersController::class,'deleteData'])->name('pic.delete');
    Route::post('/pic/edit/{id}/save',[UsersController::class,'editData'])->name('pic.edit.submit');
});
