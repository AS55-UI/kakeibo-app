<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    //ˆê——•\¦
    public function index()
    {
    	$expenses = Expense::all();
    	$total = $expenses->sum('amount');
    	
    	return view('expenses.index', compact('expenses', 'total'));
    }
    
    //“o˜^ˆ—
     public function store(Request $request)
    {
    	Expense::create($request->all());
    	
    	return redirect('/');
    }
   
   
}
