<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewController extends Controller
{
	/**
	 * View login page
	 */
	public function loginView(Request $request)
	{
		return view('admin.auth.login');
	}



}
