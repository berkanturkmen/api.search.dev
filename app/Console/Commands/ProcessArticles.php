<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessArticles extends Command
{
	protected $signature = 'app:ProcessArticles';

	protected $description = '';

	protected TheGuardianService $TheGuardianService;
	protected NewYorkTimesService $NewYorkTimesService;

	public function __construct(TheGuardianService $TheGuardianService, NewYorkTimesService $NewYorkTimesService)
	{
		parent::__construct();

		$this->TheGuardianService = $TheGuardianService;
		$this->NewYorkTimesService = $NewYorkTimesService;
	}

	public function handle()
	{
		$a = ['Finance', 'Economy', 'Politics', 'Business', 'Artificial Intelligence'];
		$b = array_rand($a);

		$data['TheGuardian'] = $this->TheGuardianService->search($a[$b], null, date('Y-m-d', strtotime('-1 month')), date('Y-m-d'));
		$data['NewYorkTimes'] = $this->NewYorkTimesService->search($a[$b], null, date('Y-m-d', strtotime('-1 month')), date('Y-m-d'));

		$array = array_merge($data['TheGuardian'], $data['NewYorkTimes']);

		foreach ($array as $value) {
			DB::table('Articles')->updateOrInsert(
				[
					'UUID' => $value['UUID'] ?? Str::uuid(),
				],
				[
					'ABSTRACT' => $value['ABSTRACT'] ?? null,
					'CATEGORY' => $value['CATEGORY'] ?? null,
					'DATE' => $value['DATE'] ?? null,
					'SOURCE' => $value['SOURCE'] ?? null,
					'TITLE' => $value['TITLE'] ?? null,
					'URL' => $value['URL'] ?? null,
					'UUID' => $value['UUID'] ?? Str::uuid(),
				]
			);
		}

		$this->info('ProcessArticles OK. | ' . $a[$b]);
	}
}
