<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Budget;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    //一覧表示
    public function index(Request $request)
    {
 //   	dd($request->date);//デバッグ用
    	
//    	$query = Expense::query();
    	$query = Expense::where('user_id', auth()->id());

    	//日付検索
			//期間指定(最優先)
		if ($request->from_date && $request->to_date) {
	    	$query->whereBetween('date', [$request->from_date, $request->to_date]);

    	} elseif ($request->from_date){
			//
	    	$query->where('date', '>=', $request->from_date);

    	} elseif ($request->to_date){
			//
	    	$query->where('date', '<=', $request->to_date);

    	} elseif ($request->date){
			//単日付優先（1日検索）
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
//  		$total = $query->sum('amount');
  		$total =(clone $query)->sum('amount');


//    	$expenses = Expense::all();
//    	$total = $expenses->sum('amount');

		//予算取得
		$budgets = Budget::all()->keyBy('month');

    	//月ごとの合計
    	$monthlyTotals = (clone $query)
    		->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
    		->groupBy('month')
    		->orderBy('month', 'desc')
    		->get();
		//カテゴリの計算
		$categoryTotals = (clone $query)
    		->selectRaw('category, SUM(amount) as total')
    		->groupBy('category')
    		->orderBy('total', 'desc')
    		->get();

//dd($monthlyTotals );
  	 		//データを取得・ページネーション
//  		$expenses = $query->get();
  		$expenses = $query->paginate(10)->withQueryString();

//    	return view('expenses.index', compact('expenses', 'total'));
//    	return view('expenses.index', compact('expenses', 'total', 'monthlyTotals'));
//    	return view('expenses.index', compact('expenses', 'total', 'monthlyTotals', 'budgets'));
    	return view('expenses.index', compact('expenses', 'total', 'monthlyTotals', 'budgets', 'categoryTotals' ));
//    	return "OK";

    }
    
    //★詳細画面（2026/06/24_追加する）
    public function show($id)
    {
		$expense = Expense::findOrFail($id);
		
		//自分のデータかチェック（超重要）
		if ($expense->user_id !== auth()->id()) {
			abort(403);
		}
		
		return view('expenses.show', compact('expense'));
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
//    	Expense::create($validated);
		//user_idを追加
    	$validated['user_id'] = auth()->id();

		//保存
    	Expense::create($validated);
		
    	
    	return redirect('/expenses');
    }

	//予算登録処理
	public function storeBudget(Request $request)
	{
		$request->validate([
			'month' => 'required',
			'budget_amount' => 'required|integer|min:1|max:1000000000',
		], [
  			'month.required' => '月を入力してください',
  			'budget_amount.required' => '金額を入力してください',
  			'budget_amount.integer' => '金額は数値で入力してください',
  			'budget_amount.min' => '金額は1円以上にしてください',
  			'budget_amount.max' => '金額が大きすぎます（10億円以下で入力して下さい）',
  		]);
	
		Budget::updateOrCreate(
			['month' => $request->month],
			['amount' => $request->budget_amount]
		);
		
		return redirect('/expenses');
	}




   
   
   
    //編集画面を出す
     public function edit($id)
    {
    	$expense = Expense::findOrFail($id);
    	
		//自分のデータかチェック（超重要）
		if ($expense->user_id !== auth()->id()) {
			abort(403);
		}

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

	//CSVダウンロード
	public function export(Request $request)
	{
//		$expenses = Expense::all();
//		$query = Expense::query();
    	$query = Expense::where('user_id', auth()->id());

    	//日付検索
			//期間指定(最優先)改良版
		if ($request->from_date && $request->to_date) {
			//両方指定→期間
	    	$query->whereBetween('date', [$request->from_date, $request->to_date]);
		
    	} elseif ($request->from_date) {

			//fromだけ→以降
	    	$query->where('date', '>=', $request->from_date);
	    	
	    } elseif ($request->to_date) {
			//toだけ→以前
	    	$query->where('date', '<=', $request->to_date);

		} elseif ($request->date) {
			//単日付優先（1日検索）
	    	$query->whereDate('date', $request->date);

    	} elseif ($request->month) {
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

		$expenses = $query->get();
		
		$csvData = [];
		
		//ヘッダー
		$csvData[] = ['日付', '内容', '金額','カテゴリ'];
		
		//データ
		foreach ($expenses as $expense) {
			$csvData[] = [
				$expense->date,
				$expense->item,
				$expense->amount,
				$expense->category,
			];
		}
		
		//合計を計算
		$total = $expenses->sum('amount');

		//合計行を追加
		$csvData[] = ['', '合計', $total,''];
		
		//CSV作成
		$filename = 'expenses.csv';
		
		$handle = fopen('php://temp', 'r+');
		
		foreach ($csvData as $row) {
			fputcsv($handle, $row);
		}
		
		rewind($handle);
		$csv = stream_get_contents($handle);
		fclose($handle);
		
		return response($csv)
			->header('Content-Type', 'text/csv')
						->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
	}







	
	
	

   
}
