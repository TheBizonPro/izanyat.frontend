<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'answers';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];


	public function question(){}
	public function player(){}
//question_id
//player_id

}
