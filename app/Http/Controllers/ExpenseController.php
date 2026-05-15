<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    //一覧表示
    public function index()
    {
    	$expenses = Expense::all();
    	$total = $expenses->sum('amount');
    	
    	return view('expenses.index', compact('expenses', 'total'));
//    	return "OK";

    }
    
    //登録処理
     public function store(Request $request)
    {
    	$validated = $request->validate([
	   		'date'	=> 'required|date',
    		'item'	=> 'required|string|max:255',
    		'amount' => 'required|integer|min:0|max:1000000000',
  		], [
  			'date.required' => '日付を入力してください',
  			'item.required' => '内容を入力してください',
  			'amount.required' => '金額を入力してください',
  			'amount.integer' => '金額は数値で入力してください',
  			'amount.min' => '金額は0以上にしてください',
  			'amount.max' => '金額が大きすぎます（10億円以下で入力して下さい）',
  		]);


    	Expense::create($request->all());
    	
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
    		'amount' => 'required|integer|min:0|max:1000000000',
  		], [
  			'date.required' => '日付を入力してください',
  			'item.required' => '内容を入力してください',
  			'amount.required' => '金額を入力してください',
  			'amount.integer' => '金額は数値で入力してください',
  			'amount.min' => '金額は0以上にしてください',
  			'amount.max' => '金額が大きすぎます（10億円以下で入力して下さい）',
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
