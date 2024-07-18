<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mycontroller;

Route::get('/login',function(){
    return view('index');
});
Route::post('/login', [MyController::class, 'login'])->name('login');

Route::get('/register',function(){
    return view('register');
});
Route::post('/register',[Mycontroller::class, 'registration']);

Route::get('/dashboard',[Mycontroller::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/logout',[Mycontroller::class, 'logout'])->name('logout');
