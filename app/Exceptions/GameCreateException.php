<?php

namespace App\Exceptions;

use Exception;
use Lang;

class GameCreateException extends Exception
{
	private $details;

	public function __construct($prevException = null)
	{
		$this->title = Lang::get('ui.error');
		$this->code = 400;
		$this->message = Lang::get('errors.game.create_error');
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
