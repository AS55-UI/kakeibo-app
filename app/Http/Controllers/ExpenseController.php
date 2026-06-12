<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    //一覧表示
    public function index(Request $request)
    {
 //   	dd($request->date);//デバッグ用
    	
    	$query = Expense::query();

    	//日付検索
    	if ($request->date){
			//日付優先（1日検索）
	    	$query->whereDate('date', $request->date);

    	} elseif ($request->month){
	    	//月検索（まとめ）
	    	$query->where('date', 'like', $request->month . '%');
    	}

    	//日付検索
//    	if ($request->date){
//	    	$query->where('date', 'like', $request->date . '%');
//    	}

    	
    	//カテゴリ検索
    	if ($request->category){
	    	$query->where('category', 'like','%' . $request->category . '%');
    	}


    	//並び替え
    	if ($request->sort == 'new'){
	    	$query->orderBy('date', 'desc')
	    			->orderBy('id', 'desc');
    	} elseif ($request->sort == 'old') {
	    	$query->orderBy('date', 'asc')
	    			->orderBy('id', 'asc');
    	}	

//     	$expenses = $query->get();
		//合計をDBで計算（軽くする）
  		$total = $query->sum('amount');
 		//データを取得
  		$expenses = $query->get();

//    	$expenses = Expense::all();
//    	$total = $expenses->sum('amount');

    	//月ごとの合計
    	$monthlyTotals = Expense::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
    		->groupBy('month')
    		->orderBy('month', 'desc')
    		->get();
    	
//    	return view('expenses.index', compact('expenses', 'total'));
    	return view('expenses.index', compact('expenses', 'total', 'monthlyTotals'));
//    	return "OK";

    }
    
    //登録処理
     public function store(Request $request)
    {
    	$validated = $request->validate([
	   		'date'	=> 'required|date',
    		'item'	=> 'required|string|max:255',
    		'amount' => 'required|integer|min:1|max:1000000000',
    		'category'	=> 'required|string|max:255',
  		], [
  			'date.required' => '日付を入力してください',
  			'item.required' => '内容を入力してください',
  			'amount.required' => '金額を入力してください',
  			'amount.integer' => '金額は数値で入力してください',
  			'amount.min' => '金額は1円以上にしてください',
  			'amount.max' => '金額が大きすぎます（10億円以下で入力して下さい）',
  			'category.required' => 'カテゴリを入力してください',
  		]);


//    	Expense::create($request->all());
    	Expense::create($validated);

    	
    	return redirect('/expenses');
    }
   
    //編集画面を出す
     public function edit($id)
    {
    	$expense = Expense::findOrFail($id);
    	return view('expenses.edit', compact('expense'));
    	
    }

    //更新処理
     public function update(Request $request, $id)
    {
		//バリエーション
/*
$request->validate(
	[
		'amount' => 'required|integer',
	],
	[
		'amount.required' => '金額は必須です。',
	]
);
*/
    	$validated = $request->validate([
	   		'date'	=> 'required|date',
    		'item'	=> 'required|string|max:255',
    		'amount' => 'required|integer|min:1|max:1000000000',
    		'category'	=> 'required|string|max:255',
  		], [
  			'date.required' => '日付を入力してください',
  			'item.required' => '内容を入力してください',
  			'amount.required' => '金額を入力してください',
  			'amount.integer' => '金額は数値で入力してください',
  			'amount.min' => '金額は1円以上にしてください',
  			'amount.max' => '金額が大きすぎます（10億円以下で入力して下さい）',
  			'category.required' => 'カテゴリを入力してください',
  		]);
    	
    	$expense = Expense::findOrFail($id);


    	$expense->update($validated);
    	
    	return redirect('/expenses');
    	
/*
    	$expense->update([
    		'date'	=> $request->date,
    		'item'	=> $request->item,
    		'amount'	=> $request->amount,
    		
    	]);
*/    		
	}

    //削除機能
     public function destroy($id)
    {
    	$expense = Expense::findOrFail($id);
    	$expense->delete();

    	return redirect('/expenses');
    	
    }







	
	
	

   
}
