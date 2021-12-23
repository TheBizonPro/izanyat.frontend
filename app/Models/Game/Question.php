<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'questions';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];

//block_id

	//block
	//automaticChecks
	//answers
}
