<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use Auth;

class UserController extends Controller
{
	/**
	 * 
	 */
	public function settingsSave(Request $request)
	{
		$me = Auth::user();
		$me->fill($request->all());
		$me->save();

		return response()
			->json([
				'message' => Lang::get('ui.successful_saving') . '!',
				'redirect_to' => route('admin.games.index')
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}
}
