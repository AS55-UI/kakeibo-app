<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    public function index()
    {
    	$items = Item::all();//DB‚©‚ç‘S•”Žæ‚é
		return view('items.index', compact('items'));
	}

    public function store(Request $request)
    {

    	$request->validate([
    		'name' => 'required|max:100',
    	]);
//    	dd($request->all());
    	$items = new Item();
    	$items->name = $request->name;
    	$items->save();

		
		return redirect('/items');
	}

/*
{
    public function index()
    {
    	$items = Item::all();//DB‚©‚ç‘S•”Žæ‚é
		return view('items.index', compact('items'));
	}

}
*/




}