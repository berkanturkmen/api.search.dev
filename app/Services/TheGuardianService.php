<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TheGuardianService
{
	protected $HTTP;
	protected $API_KEY;

	public function __construct()
	{
		$this->URL = config('External.TheGuardian.URL');
		$this->API_KEY = config('External.TheGuardian.API_KEY');
	}

	public function search(string $a, ?string $b = null, ?string $c = null, ?string $d = null): array
	{
		$params = [
			'api-key' => $this->API_KEY,
			'page-size' => 15,
			'q' => $a,
			'show-fields' => 'headline,thumbnail,trailText',
		];

		if ($b !== null) {
			$params['section'] = $b;
		}

		if ($c !== null) {
			$params['from-date'] = $c;
		}

		if ($d !== null) {
			$params['to-date'] = $d;
		}

		$res = Http::get($this->URL . 'search', $params);

		if ($res->successful()) {
			return $this->transform($res->json());
		}
	}

	protected function transform(array $array): array
	{
		$data = [];

		if (isset($array['response']['results'])) {
			foreach ($array['response']['results'] as $value) {
				$data[] = [
					'ABSTRACT' => $value['fields']['trailText'] ?? null,
					'CATEGORY' => $value['sectionName'] ?? null,
					'DATE' => isset($value['webPublicationDate']) ? date('Y-m-d', strtotime($value['webPublicationDate'])) : null,
					'SOURCE' => 'The Guardian',
					'TITLE' => $value['fields']['headline'] ?? null,
					'URL' => $value['webUrl'] ?? null,
					'UUID' => $value['id'] ?? null,
				];
			}
		}

		return $data;
	}
}
