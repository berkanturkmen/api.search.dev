<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		//
	}

	public function boot(): void
	{
		\Illuminate\Support\Facades\Validator::resolver(function ($translator, $data, $rules, $messages) {
			return new class($translator, $data, $rules, $messages) extends \Illuminate\Validation\Validator {
				protected function getAttributeFromTranslations($attribute)
				{
					return strtoupper($attribute);
				}
			};
		});
	}
}
