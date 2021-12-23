<?php

namespace App\Http\Controllers\Admin\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

use App\Exceptions\PlayerNotExistsException;
use App\Exceptions\PlayerValidateException;
use App\Exceptions\PlayerSaveException;

use Yajra\DataTables\Facades\DataTables;
use DB;
use Lang;
use Player;
use Group;

class PlayerController extends Controller
{
	/**
	 * Get players asa a datatable
	 */
	public function datatable(Request $request)
	{
		$players = Player::leftJoin('groups', 'groups.id', '=', 'players.group_id');
		$players = $players->select([
			DB::raw('players.*'),
			DB::raw('groups.name as group_name')
		]);

		$dataTable = DataTables::eloquent($players);

		$dataTable = $dataTable->filterColumn('group_name', function($query, $keyword) {
			$query->whereRaw('groups.name like ?', ["%{$keyword}%"]);
		});

		$dataTable = $dataTable->orderColumn('group_name', function ($query, $order) {
			$query->orderBy('groups.name', $order);
		});

		$dataTable = $dataTable->orderColumn('login', function ($query, $order) {
			$query->orderByRaw('LENGTH(login) ' . $order)->orderBy('login', $order);
		});

		$dataTable = $dataTable->orderColumn('name', function ($query, $order) {
			$query->orderByRaw('LENGTH(players.name) ' . $order)->orderBy('players.name', $order);
		});


		$dataTable = $dataTable->addColumn('group_name', function(Player $player) {
			return $player->group_name;
		});

		$dataTable = $dataTable->smart(true);
		return $dataTable->make(true);
	}


	/**
	 * Get player data
	 */
	public function get(Request $request)
	{
		$player = Player::find($request->player_id);

		return response()
			->json([
				'player' => $player,
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}


	/**
	 * Create new player
	 */
	public function new(Request $request)
	{
		sleep(1);
		/**
		 * Getting next player Number
		 */
		$lastTeamN = Player::getLastTeamN('team');
		$nextTeamN = $lastTeamN + 1;

		/**
		 * Creating player
		 */
		$player = new Player;
		$player->password = rand(1000000, 9999999);
		$player->name = Lang::get('ui.new_player') . ' ' . $nextTeamN;
		$player->login = 'team' . $nextTeamN;
		$player->save();
		$player->refresh();

		return response()
			->json([
				'player' => $player,
				'title' => 'Успешно',
				'message' => 'Новый участник создан',
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}


	/**
	 * Save user data
	 */
	public function save(Request $request)
	{
		$player = Player::find($request->player_id);
		if ($player == null) {
			throw new PlayerNotExistsException();
		}

		/**
		 * Validate data
		 */
		$validationSchema = [
			'name' => ['required'],
			'group_id' => ['nullable', 'integer'],
			'login' => ['required', Rule::unique('game.players', 'login')->ignore($request->player_id)],
			'password' => ['required'],
			'email' => ['nullable', 'email', Rule::unique('game.players', 'email')->ignore($request->player_id)],
			'phone' => ['nullable'],
		];

		$validationMessages = [
			'name.required' => Lang::get('errors.player.name.required'),
			'group_id.integer' => Lang::get('errors.player.group_id.integer'),
			'login.required' => Lang::get('errors.player.login.required'),
			'login.unique' => Lang::get('errors.player.login.unique'),
			'password.required' => Lang::get('errors.player.password.required'),
			'email.email' => Lang::get('errors.player.email.email'),
			'email.unique' => Lang::get('errors.player.email.unique'),
		];

		try {
			$validatedData = $request->validate($validationSchema, $validationMessages);
		} catch (\Throwable $e) {
			throw new PlayerValidateException($request->player_id, $e);
		}


		$player->fill($request->all());
		try {
			$player->save();
		} catch (\Throwable $e) {
			throw new PlayerSaveException($request->player_id, $e);
		}

		$player->refresh();

		return response()
			->json([
				'player' => $player,
				'title' => 'Успешно',
				'message' => 'Данные участника сохранены',
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}


	/**
	 * Players mass creation
	 */
	public function massCreate(Request $request)
	{
		$count = intval($request->count);
		if ($count > 500) {
			$count = 500;
		}

		$group_id = $request->group_id;
		if ($group_id != null) {
			$group = Group::find($group_id);
			if ($group == null) {
				$group_id = null;
			}
		}

		/**
		 * Getting next player Number
		 */
		$lastTeamN = Player::getLastTeamN('team');
		$nextTeamN = $lastTeamN + 1;

		DB::beginTransaction();
		for ($i = 0; $i < $count; $i++) { 
			/**
			 * Creating player
			 */
			$player = new Player;
			$player->password = rand(1000000, 9999999);
			$player->name = Lang::get('ui.new_player') . ' ' . $nextTeamN;
			$player->login = 'team' . $nextTeamN;
			$player->group_id = $group_id;
			$player->save();
			
			$nextTeamN++;
		}
		DB::commit();


		return response()
			->json([
				'title' => 'Успешно',
				'message' => 'Создано ' . $count . ' новых участников',
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);


	}



	/**
	 * Generate new password
	 */
	public function generatePassword(Request $request)
	{
		$password = rand(1000000, 9999999);
		return response()
			->json([
				'password' => $password,
				'title' => 'Успешно',
				'message' => 'Новый пароль сгенерирован',
			], 200, [], JSON_UNESCAPED_UNICODE||JSON_UNESCAPED_SLASHES);
	}
}
