<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PreferenceController extends Controller
{
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'PREFERENCES' => ['required', 'max:255', 'regex:/^[^,]+(,\s*[^,]+)*$/'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$user = User::where('API_TOKEN', $request->user()->API_TOKEN)->first();

		if (!$user) {
			return response()->json([], 401);
		}

		$user->update([
			'PERMANENT_STORAGE' => json_encode([
				'PREFERENCES' => explode(',', $request->PREFERENCES),
			]),
		]);

		return response()->json([
			'data' => $user,
		]);
	}
}
