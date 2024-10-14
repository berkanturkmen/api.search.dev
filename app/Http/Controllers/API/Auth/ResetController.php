<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetController extends Controller
{
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'EMAIL' => ['required', 'max:255', 'email'],
			'PASSWORD' => ['required', 'min:8', 'max:255'],
			'CODE' => ['required', 'min:6', 'max:6'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$user = User::where('EMAIL', $request->EMAIL)->first();

		if (!$user || !$user->TEMP_STORAGE) {
			return response()->json([
				'errors' => [
					'EMAIL' => ['The provided credentials are incorrect.'],
				],
			]);
		}

		$data['TEMP_STORAGE'] = json_decode($user->TEMP_STORAGE, true);

		if (!$data['TEMP_STORAGE'] || time() > $data['TEMP_STORAGE']['PASSWORD_RESET']['VALIDITY_TIME']) {
			return response()->json([
				'errors' => [
					'CODE' => ['Error!'],
				],
			]);
		}

		if ($request->CODE != $data['TEMP_STORAGE']['PASSWORD_RESET']['CODE']) {
			return response()->json([
				'errors' => [
					'CODE' => ['Error!'],
				],
			]);
		}

		$user->update([
			'PASSWORD' => Hash::make($request->PASSWORD),
			'TEMP_STORAGE' => null,
		]);

		return response()->json([], 204);
	}
}
