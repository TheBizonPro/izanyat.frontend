<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'attachments';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];


//block_id

	public function block(){}
}
