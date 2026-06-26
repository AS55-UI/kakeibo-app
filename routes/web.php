<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

Route::get('/', function() {
	if (auth()->check()) {
		return redirect('/expenses');
	}
	return redirect('/login');
});

Route::middleware('auth')->group(function() {
	Route::get('/expenses/export', [ExpenseController::class, 'export'])
		->name('expenses.export');

	
	Route::resource('expenses', ExpenseController::class);

	Route::post('/budgets', [ExpenseController::class, 'storeBudget']);


	Route::get('/dashboard', function () {
		return redirect('/expenses');
	})->name('dashboard');
});

require __DIR__.'/auth.php';


//Route::get('/lang-check',function() {
//	return app()->getLocale();
//});

//Route::get('/', [ExpenseController::class, 'index']);
//Route::post('/store', [ExpenseController::class, 'store']);




/*crud処理練習
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ItemController;


Route::get('/', function () {
    return view('hello');
});

//5/4
Route::get('/items', [ItemController::class, 'index']);
Route::post('/items', [ItemController::class, 'store']);
Route::get('/items/{id}/edit', [ItemController::class, 'edit']);//編集処理
Route::put('/items/{id}', [ItemController::class, 'update']);   //更新処理
Route::delete('/items/{id}', [ItemController::class, 'destroy']);//削除処理
*/

/*
Route::get('/hello', function () {
    return "Helloページです";
});
*/
/*
Route::get('/about', function () {
    return "このアプリについて";
});

//4/24追加

Route::get('/hello',[HelloController::class, 'index']);
*/
//Route::get('/items',[ItemController::class, 'index']);

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
