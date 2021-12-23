<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
	use SoftDeletes;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'general';
	protected $table = 'games';
	protected $primaryKey = 'id';
	protected $keyType = 'int';
	public $incrementing = true;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];


	/**
	 * Getter of game playzone url
	 */
	public function getPlayUrlAttribute()
	{
		if ($this->domain == null) {
			return null;
		}
		return 'https://' . $this->domain; 
	}

}
