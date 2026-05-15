<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
    	$items = Item::all();//DB‚©‚ç‘S•”Žæ‚é
		return view('items.index', compact('items'));
	}
}
