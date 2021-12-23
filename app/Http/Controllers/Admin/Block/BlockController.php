<?php

namespace App\Http\Controllers\Admin\Block;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Block;

class BlockController extends Controller
{
	/**
	 * Get blocks datatable
	 */
	public function datatable(Request $request)
	{
		//sleep(10);
		$blocks = Block::query();
		$dataTable = DataTables::eloquent($blocks);
		$dataTable = $dataTable->smart(true);
		return $dataTable->make(true);
	}

	/**
	 * Get block data
	 */
	public function get(Request $request)
	{
		//sleep(2);

		$block = Block::find($request->block_id);


		return response()
			->json([
				'block' => $block,
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}

}
