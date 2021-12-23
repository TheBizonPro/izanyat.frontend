<?php

namespace App\Http\Controllers\Admin\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;

class ViewController extends Controller
{
	
	/**
	 * View game groups index page
	 */
	public function indexView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'groups'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.group.index', $with);
	}
}
