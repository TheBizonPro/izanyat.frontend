<?php

namespace App\Http\Controllers\Admin\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;
class ViewController extends Controller
{
	/**
	 * View players index page
	 */
	public function indexView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'players'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.player.index', $with);
	}
}
