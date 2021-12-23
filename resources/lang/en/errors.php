<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Errors Language Lines
	|--------------------------------------------------------------------------
	*/
	'game' => [
		'create_error'   => 'Error creating game!',
		'saving_error'   => 'Error saving game!',
		'not_exists'     => 'Game #:id not exists!',
		'not_accessible' => 'You have no access to game #:id!',
	],


	'player' => [
		'validation_error' => 'Some fields are filled wrong',
		'name' => [
			'required' => 'Player name not set',
		],
		'group_id' => [
			'integer' => 'Invalid route',
		],
		'login' => [
			'required' => 'Player login not set',
			'unique' => 'This login is already used',
		],
		'password' => [
			'required' => 'Password not set',
		],
		'email' => [
			'email' => 'This email is already used',
			'unique' => 'Email format is invalid',
		],
	],

];
