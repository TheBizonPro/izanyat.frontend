<?php

namespace App\Http\Controllers\Admin\Block;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;
	
class ViewController extends Controller
{
	/**
	 * View blocks index page
	 */
	public function indexView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'blocks'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.block.index', $with);
	}
}