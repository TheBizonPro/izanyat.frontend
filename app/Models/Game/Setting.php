<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

	/**
	 * Таблица и настройки индекса
	 */
	protected $connection = 'game';
	protected $table = 'settings';
	protected $primaryKey = 'name';
	protected $keyType = 'string';
	public $incrementing = false;
	protected $guarded = ['*'];
	public $timestamps = true;
	
	public $fillable = [];
	
	public $hidden = [];
	
	public $appends = [];



	/**
	 * Статический метод получения значения настройки
	 */
	public static function get($name)
	{
		$setting = (new static)::where('name', $name)->first();
		return $setting->value ?? null;
	}


	/**
	 * Статический метод получения значения настройки
	 */
	public static function set($name, $value)
	{
		$setting = (new static)::where('name', $name)->first();
		$setting =  $setting ?? new Setting;
		$setting->name = $name;
		$setting->value = $value;
		$setting->save();
		return $value;
	}

}
