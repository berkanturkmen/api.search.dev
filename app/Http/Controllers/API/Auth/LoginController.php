<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'EMAIL' => ['required', 'max:255', 'email'],
			'PASSWORD' => ['required', 'min:8', 'max:255'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$user = User::where('EMAIL', $request->EMAIL)->first();

		if (!$user || !Hash::check($request->PASSWORD, $user->PASSWORD)) {
			return response()->json([
				'errors' => [
					'EMAIL' => ['The provided credentials are incorrect.'],
				],
			]);
		}

		$user->update([
			'API_TOKEN' => Str::random(60),
		]);

		return response()->json([
			'data' => $user,
		]);
	}
}
