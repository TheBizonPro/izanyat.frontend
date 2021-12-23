<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
	public function logout(Request $request)
	{
		Auth::logout();
		return redirect(route('login'));
	}
}
