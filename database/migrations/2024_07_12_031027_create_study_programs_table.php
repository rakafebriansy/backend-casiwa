<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->unique()->nullable(false);
            $table->unsignedBigInteger('faculty_id')->nullable(false);

            $table->foreign('faculty_id')->on('faculties')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};