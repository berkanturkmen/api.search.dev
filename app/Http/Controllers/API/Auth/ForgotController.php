<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ForgotController extends Controller
{
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'EMAIL' => ['required', 'max:255', 'email'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$user = User::where('EMAIL', $request->EMAIL)->first();

		if (!$user) {
			return response()->json([
				'errors' => [
					'EMAIL' => ['The provided credentials are incorrect.'],
				],
			]);
		}

		$data['TEMP_STORAGE'] = json_decode($user->TEMP_STORAGE, true);

		if (isset($data['TEMP_STORAGE']['PASSWORD_RESET']['NEW_CODE_TIME']) && time() <= $data['TEMP_STORAGE']['PASSWORD_RESET']['NEW_CODE_TIME']) {
			return response()->json([
				'errors' => [
					'EMAIL' => ['Error!'],
				],
			]);
		}

		$user->update([
			'TEMP_STORAGE' => json_encode([
				'PASSWORD_RESET' => [
					'CODE' => rand(100000, 999999),
					'NEW_CODE_TIME' => time() + 60,
					'VALIDITY_TIME' => time() + 1800,
				],
			]),
		]);

		return response()->json([], 204);
	}
}
