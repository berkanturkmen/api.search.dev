<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	protected $policies = [];

	public function boot(): void
	{
		$this->registerPolicies();

		\Illuminate\Support\Facades\Auth::viaRequest('token', function ($request) {
			return \App\Models\User::where('API_TOKEN', $request->bearerToken())->first();
		});
	}
}
