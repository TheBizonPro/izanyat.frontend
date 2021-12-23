<?php

namespace App\Http\Controllers\Admin\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Group;
class GroupController extends Controller
{
	/**
	 * List all groups
	 */
	public function list(Request $request)
	{
		$groups = Group::orderBy('sort')->get();
		return response()
			->json([
				'groups' => $groups
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}
}
