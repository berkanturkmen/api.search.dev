<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
	protected TheGuardianService $TheGuardianService;
	protected NewYorkTimesService $NewYorkTimesService;

	public function __construct(TheGuardianService $TheGuardianService, NewYorkTimesService $NewYorkTimesService)
	{
		$this->TheGuardianService = $TheGuardianService;
		$this->NewYorkTimesService = $NewYorkTimesService;
	}

	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'Q' => ['required', 'string', 'max:255'],
			'C' => ['sometimes', 'string', 'max:255'],
			'SD' => ['sometimes', 'string', 'date_format:Y-m-d'],
			'ED' => ['sometimes', 'string', 'date_format:Y-m-d'],
			'S' => ['sometimes', 'string', 'max:255'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors(),
			]);
		}

		$params['Q'] = $request->Q;
		$params['C'] = $request->C ?? null;
		$params['SD'] = $request->SD ?? null;
		$params['ED'] = $request->ED ?? null;
		$params['S'] = $request->S ? explode(',', $request->S) : ['The Guardian', 'New York Times'];

		$data = [];

		if (in_array('The Guardian', $params['S'])) {
			$_data['TheGuardian'] = $this->TheGuardianService->search($params['Q'], $params['C'], $params['SD'], $params['ED']);

			$data = array_merge($data, $_data['TheGuardian']);
		}

		if (in_array('New York Times', $params['S'])) {
			$_data['NewYorkTimes'] = $this->NewYorkTimesService->search($params['Q'], $params['C'], $params['SD'], $params['ED']);

			$data = array_merge($data, $_data['NewYorkTimes']);
		}

		shuffle($data);

		return response()->json([
			'data' => $data,
		]);
	}
}
