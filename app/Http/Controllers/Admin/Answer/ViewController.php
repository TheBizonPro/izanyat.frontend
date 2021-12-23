<?php

namespace App\Http\Controllers\Admin\Answer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Game;

class ViewController extends Controller
{

	/**
	 * View jury view
	 */
	public function juryView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'jury'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.answer.jury', $with);
	}


	/**
	 * View results page
	 */
	public function resultsView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => 'results'];
		$with['game'] = Game::find($request->game_id);
		return view('admin.answer.results', $with);
	}
	
}