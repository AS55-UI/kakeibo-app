<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ItemController;


Route::get('/', function () {
    return view('hello');
});

//5/4
Route::get('/items', [ItemController::class, 'index']);
Route::post('/items', [ItemController::class, 'store']);



/*
Route::get('/hello', function () {
    return "Helloページです";
});
*/

Route::get('/about', function () {
    return "このアプリについて";
});

//4/24追加

Route::get('/hello',[HelloController::class, 'index']);

Route::get('/items',[ItemController::class, 'index']);

/*
Route::get('/hello', function () {
    return "hello Laravel!";
});

use Illuminate\Support\Facades\DB;

Route::get('/test-db', function () {
    $data = DB::select("SELECT 1+1 AS result");
    return $data;
});
*/
