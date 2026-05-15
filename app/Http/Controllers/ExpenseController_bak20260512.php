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
    }
    
    //登録処理
     public function store(Request $request)
    {
    	Expense::create($request->all());
    	
    	return redirect('/');
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
    		'amount' => 'required|integer|min:0',
  		]);
    	
    	$expense = Expense::findOrFail($id);


    	$expense->update($validated);
    	
    	
/*
    	$expense->update([
    		'date'	=> $request->date,
    		'item'	=> $request->item,
    		'amount'	=> $request->amount,
    		
    	]);
*/    		
    	return redirect('/expenses');
    	
    }

   
}
