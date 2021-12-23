<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\View;
use Throwable;


use App\Exceptions\GameNotExistsException;
use App\Exceptions\GameNotAccessibleException;


class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];


	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];


	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}


	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Throwable  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Throwable $exception)
	{

		$appExceptions = [
			
			'App\Exceptions\GameCreateException',
			'App\Exceptions\GameSaveException',
			'App\Exceptions\GameNotExistsException',
			'App\Exceptions\GameNotAccessibleException',
			
			'App\Exceptions\PlayerSaveException',
			'App\Exceptions\PlayerNotExistsException',
			'App\Exceptions\PlayerValidateException',

		];

		$exceptionClass = get_class($exception);

		if (in_array($exceptionClass, $appExceptions)) {
			$title = $exception->getTitle();
			$message = $exception->getMessage();
			$code = $exception->getCode();
			$details = $exception->getDetails();
			if (in_array($code, [400, 401, 402, 403, 404, 405, 419, 500]) == false) {
				$code = 500;
			}
		    if ($request->ajax()) {
				return response()
					->json([
						'ok' => false,
						'message' => $message,
						'details' => $details,
						'title' => $title
					], $code, [], JSON_UNESCAPED_UNICODE);
		    } else {
		    	$data = ['message' => $message, 'details' => $details];
				if (View::exists('errors.' . $code)) {
					return response()->view('errors.' . $code, $data, $code);
				} else {
					return response($message, $code)->header('Content-Type', 'text/plain');
				}
		    }
		}

 		return parent::render($request, $exception);
	}

	
}
