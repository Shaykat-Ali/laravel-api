<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::resource('users',UserController::class)->only('index','store','update','destroy');;
