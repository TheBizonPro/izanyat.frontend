<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'locations';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = false;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];

	public function player(){}
//player_id
}
