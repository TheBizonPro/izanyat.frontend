<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;

class ViewController extends Controller
{
	/**
	 * View game appearance page
	 */
	public function appearanceView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'appearance'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.settings.appearance', $with);
	}
}
