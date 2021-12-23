<?php

namespace App\Exceptions;

use Exception;
use Lang;

class PlayerNotExistsException extends Exception
{
	private $details;

	public function __construct(int $playerId, $prevException = null)
	{
		$this->title = Lang::get('ui.error');
		$this->code = 404;
		$this->message = Lang::get('errors.player.not_exists', ['id' => $playerId]);
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
