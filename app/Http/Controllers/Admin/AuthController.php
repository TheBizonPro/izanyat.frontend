<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use User;

class AuthController extends Controller
{
	//2do remove this file


	public function authStatus(Request $request)
	{
		$U = Auth::user();
		dump($U);
	}


	public function demoAuth(Request $request)
	{
		$User = User::first();
		Auth::login($User, true);
		return view('login_ok', ['User' => $User]);
	}


}
