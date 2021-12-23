<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ViewController extends Controller
{
	/**
	 * View settings page
	 */
	public function settingsView(Request $request)
	{
		$me = Auth::user();
		$with = ['me' => $me, 'menu' => null];
		return view('admin.user.settings', $with);
	}
}