<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowCondition extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'show_conditions';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];


	public function block(){}
	public function targetBlock(){}

//block_id
//target_block_id

}
