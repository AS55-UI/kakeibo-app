<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    public function index()
    {
    	$items = Item::all();//DBから全部取る
		return view('items.index', compact('items'));
	}

    public function store(Request $request)
    {

    	$request->validate(
    		[
    			'name' => 'required|max:100'
	    	],
       		[
       			'name.required' => '名前は必須です'
       		],
       		[
       			'name.max' => '名前は100文字以内で入力してください'
    		],
    	);
//    	dd($request->all());
    	$item = new Item();
    	$item->name = $request->name;
    	$item->save();

		
		return redirect('/items');
	}

    public function destroy($id)
    {
    	$item = Item::find($id);

		if ($item){
	    	$item->delete();
		}
		
		return redirect('/items');
    
    }
//編集画面表示
    public function edit($id)
    {
	   	$item = Item::findOrFail($id);
//
//		if ($item){
//	    	$item->delete();
//		}
		
		return view('items.edit', compact('item'));
    }
//更新処理
    public function update(Request $request, $id)
    {

    	$request->validate([
    		['name' => 'required|max:100'],
    	],
    	[
       		['name.required' => '名前は必須です'],
       		['name.max' => '名前は100文字以内で入力してください'],
    	]);
//    	dd($request->all());
//	   	$item = Item::findOrFail($id);

    	$item = Item::find($id);
    	$item->name = $request->name;
    	$item->save();

		
		return redirect('/items');
	}


/*
{
    public function index()
    {
    	$items = Item::all();//DBから全部取る
		return view('items.index', compact('items'));
	}

}
*/




}