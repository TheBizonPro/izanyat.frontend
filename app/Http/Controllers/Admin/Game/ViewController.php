<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;
class ViewController extends Controller
{
	/**
	 * View game index page
	 */
	public function indexView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'index'];
		$with['games'] = Game::where('user_id', '=', $me->id)->orderBy('created_at', 'desc')->get();
		return view('admin.game.index', $with);
	}
	
	/**
	 * View game summary page
	 */
	public function summaryView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'summary'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.game.view', $with);
	}

	
	/**
	 * View game settings page
	 */
	public function settingsView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'settings'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.game.settings', $with);
	}
}
