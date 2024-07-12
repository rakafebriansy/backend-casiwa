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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',60)->nullable(false);
            $table->string('email',255)->unique()->nullable(false);
            $table->string('password',255)->nullable(false);
            $table->string('ktp_image',50)->nullable(false);
            $table->integer('starting_year')->nullable(false);
            $table->string('account_number',50)->unique()->nullable();
            $table->unsignedBigInteger('university_id')->nullable(false);
            $table->unsignedBigInteger('study_program_id')->nullable(false);

            $table->foreign('university_id')->on('universities')->references('id')->nullable(false);
            $table->foreign('study_program_id')->on('study_programs')->references('id')->nullable(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
