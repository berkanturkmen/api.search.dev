<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
	protected $levels = [
		//
	];

	protected $dontReport = [
		//
	];

	protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

	public function register(): void
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	public function render($request, Throwable $e)
	{
		if ($e instanceof \Illuminate\Auth\AuthenticationException) {
			return response()->json([], 401);
		}

		return parent::render($request, $e);
	}
}
