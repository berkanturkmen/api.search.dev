<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'EMAIL' => ['required', 'max:255', 'email', 'unique:users'],
			'PASSWORD' => ['required', 'min:8', 'max:255'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$user = User::create([
			'EMAIL' => $request->EMAIL,
			'PASSWORD' => Hash::make($request->PASSWORD),
			'API_TOKEN' => Str::random(60),
		]);

		return response()->json([
			'data' => $user,
		]);
	}
}
