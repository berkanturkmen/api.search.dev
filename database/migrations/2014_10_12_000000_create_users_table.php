<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('EMAIL')->unique();
			$table->string('PASSWORD');
			$table->timestamps();
		});

		Schema::table('users', function (Blueprint $table) {
			$table
				->string('API_TOKEN', 80)
				->after('PASSWORD')
				->unique();
		});

		Schema::table('users', function (Blueprint $table) {
			$table->json('PERMANENT_STORAGE')->nullable();
			$table->json('TEMP_STORAGE')->nullable();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('users');
	}
};
