<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('Articles', function (Blueprint $table) {
			$table->id();
			$table->string('ABSTRACT')->nullable();
			$table->string('CATEGORY')->nullable();
			$table->date('DATE')->nullable();
			$table->string('SOURCE');
			$table->string('TITLE')->nullable();
			$table->string('URL')->nullable();
			$table->string('UUID')->unique();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('Articles');
	}
};
