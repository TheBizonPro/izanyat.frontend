<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Exceptions\GameCreateException;
use App\Exceptions\GameSaveException;
use App\Services\CreateGameDatabase;

use DB;
use Lang;
use Auth;
use User;
use Game;

class GameController extends Controller
{
	/**
	 * Create game
	 */
	public function create(Request $request)
	{
		$me = Auth::user();

		DB::beginTransaction();

		/** 
		 * Creating new game
		 */
		$game = new Game;	
		$game->user_id = $me->id;
		$game->name = $request->name;
		try {
			$game->save();
			$game->refresh();
		} catch (\Throwable $e) {
			DB::rollBack();
			throw new GameCreateException($e);
		}

		/** 
		 * Save name of new game database
		 */
		try {
			$game->database = env('APP_GAME_DATABASE_PREFIX') . $game->id;
			$game->save();
		} catch (\Throwable $e) {
			DB::rollBack();
			throw new GameSaveException($game->id, $e);
		}

		/** 
		 * Save name of new game database
		 */
		try {
			$gameDbCreator = new CreateGameDatabase($game->database, ['name' => $game->name]);
			$gameDbCreator->createDatabase();
			$gameDbCreator->createTables();
		} catch (\Throwable $e) {
			DB::rollBack();
			throw new GameCreateException($e);
		}

		/** 
		 * Commit, if everything is ok
		 */
		DB::commit();

		return response()
			->json([
				'message' => Lang::get('ui.game_create_success') . '!',
				'redirect_to' => route('admin.game.summary', ['game_id' => $game->id])
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Removing the game
	 */
	public function remove(Request $request)
	{
		$me = Auth::user();
		$game = Game::find($request->game_id);
		$game->delete();

		return response()
			->json([
				'message' => Lang::get('ui.game_removed_success') . '!',
				'redirect_to' => route('admin.game.summary', ['game_id' => $game->id])
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}



	/**
	 * Get games as datatable
	 */
	public function datatable(Request $request)
	{
		$me = Auth::user();
		$games = Game::where('user_id', '=', $me->id);
		$dataTable = DataTables::eloquent($games);
		$dataTable = $dataTable->smart(true);

		$dataTable = $dataTable->addColumn('link', function(Game $Game) {
			return route('admin.game.summary', ['game_id' => $Game->id]);
		});

		 
		return $dataTable->make(true);

	}

}
