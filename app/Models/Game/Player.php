<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use DB;

class Player extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'players';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [
		'name',
		'group_id',
		'login',
		'password',
		'email',
		'phone',
	];
	
	public $hidden = [];
	
	public $appends = [];

	public function group(){}
	public function locations(){}
	public function answers(){}


	/**
	 * Getting the number of last player login by prefix
	 */
	public static function getLastTeamN($prefix = 'team')
	{
		$logins = DB::connection('game')
			->table('players')
			->where('login', 'like', $prefix . '%')
			->select('login')
			->get()
			->pluck('login');

		$lastTeamN = 0;
		$logins->sort()->each(function ($item, $key) use(&$lastTeamN, $prefix) {
			preg_match('/^' . $prefix . '([0-9]+)/', $item, $matches);
			$teamN = Arr::get($matches, 1);
			if (is_numeric($teamN)) {
				$lastTeamN = $teamN > $lastTeamN ? $teamN : $lastTeamN;
			}
		});

		return $lastTeamN;
	}
}
