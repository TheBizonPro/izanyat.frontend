<?php

namespace App\Exceptions;

use Exception;
use Lang;

class PlayerSaveException extends Exception
{
	private $details;

	public function __construct(int $playerId = null, $prevException = null)
	{
		$this->title = Lang::get('ui.error');
		$this->code = 400;
		$this->message = Lang::get('errors.player.saving_error', ['id' => $playerId]);
		if ($prevException) {
			$this->details = $prevException->getMessage();
		}
	}


	public function getDetails()
	{
		return $this->details;
	}

	public function getTitle()
	{
		return $this->title;
	}
}
