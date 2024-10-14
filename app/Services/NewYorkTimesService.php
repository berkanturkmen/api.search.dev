<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewYorkTimesService
{
	protected $HTTP;
	protected $API_KEY;

	public function __construct()
	{
		$this->URL = config('External.NewYorkTimes.URL');
		$this->API_KEY = config('External.NewYorkTimes.API_KEY');
	}

	public function search(string $a, ?string $b = null, ?string $c = null, ?string $d = null): array
	{
		$params = [
			'api-key' => $this->API_KEY,
			'page' => 0,
			'q' => $a,
		];

		if ($b !== null) {
			$params['fq'] = 'news_desk:("' . $b . '")';
		}

		if ($c !== null) {
			$params['begin_date'] = date('Ymd', strtotime($c));
		}

		if ($d !== null) {
			$params['end_date'] = date('Ymd', strtotime($d));
		}

		$res = Http::get($this->URL . 'svc/search/v2/articlesearch.json', $params);

		if ($res->successful()) {
			return $this->transform($res->json());
		}
	}

	protected function transform(array $array): array
	{
		$data = [];

		if (isset($array['response']['docs'])) {
			foreach (array_slice($array['response']['docs'], 0, 15) as $value) {
				$data[] = [
					'ABSTRACT' => $value['abstract'] ?? ($value['lead_paragraph'] ?? null),
					'CATEGORY' => $value['news_desk'] ?? null,
					'DATE' => isset($value['pub_date']) ? date('Y-m-d', strtotime($value['pub_date'])) : null,
					'SOURCE' => 'New York Times',
					'TITLE' => $value['headline']['main'] ?? null,
					'URL' => $value['web_url'] ?? null,
					'UUID' => $value['_id'] ?? null,
				];
			}
		}

		return $data;
	}
}
