<?php

namespace App\Exceptions;

use Exception;
use Lang;

class PlayerValidateException extends Exception
{
	private $details;

	public function __construct(int $playerId = null, $prevException = null)
	{
		$this->title = Lang::get('ui.error');
		$this->code = 400;
		$this->message = Lang::get('errors.player.validation_error', ['id' => $playerId]);
		if (get_class($prevException) == 'Illuminate\Validation\ValidationException') {
			$errorsBag = $prevException->errors();
			foreach ($errorsBag as $field => $errors) {
				foreach ($errors as &$error) {
					$error = Lang::get('errors.player.' . $field . '.' . $error);
				}
			}
			$this->details = $errorsBag;
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