<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\User;

class ExploreController extends Controller
{
	public function __invoke(Request $request)
	{
		$user = User::where('API_TOKEN', $request->user()->API_TOKEN)->first();

		if (!$user) {
			return response()->json([], 401);
		}

		$data['PERMANENT_STORAGE'] = json_decode($user->PERMANENT_STORAGE, true);
		$data['PREFERENCES'] = $data['PERMANENT_STORAGE']['PREFERENCES'];

		$data['ARTICLES'] = Article::where(function ($query) use ($data) {
			foreach ($data['PREFERENCES'] as $value) {
				$query->orWhere('TITLE', 'REGEXP', $value);
			}
		})
			->orderByDesc('DATE')
			->take(100)
			->get();

		return response()->json([
			'data' => $data['ARTICLES'],
		]);
	}
}
