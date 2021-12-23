<?php

namespace App\Services;
use Setting;

class SelectGameDatabase
{
	/**
	 * Выбло базы данных и конфигурация
	 */
	public static function byDatabaseName($databaseName)
	{
		config(['database.connections.game.database' => $databaseName]);

		$timezone = Setting::get('timezone');
		if (isset($timezone)) {
			config(['app.timezone' => $timezone]);
		}

/*		$language = Setting::get('language');
		if (isset($language)) {
			config(['app.locale' => $language]);
		}*/
	}
}