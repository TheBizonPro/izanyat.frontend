<?php

namespace App\Exceptions;

use Exception;
use Lang;

class GameNotAccessibleException extends Exception
{
	private $details;

	public function __construct(int $gameId, $prevException = null)
	{
		$this->title = Lang::get('ui.error');
		$this->code = 403;
		$this->message = Lang::get('errors.game.not_accessible', ['id' => $gameId]);
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