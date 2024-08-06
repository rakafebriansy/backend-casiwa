<?php

use Carbon\Carbon;
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
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->charset('utf8')->collation('utf8_unicode_ci')->primary();
            $table->string('token')->charset('utf8')->collation('utf8_unicode_ci')->nullable(false);
            $table->timestamp('created_at')->nullable(false)->default(Carbon::now());
            $table->timestamp('expired_at')->nullable(false)->default(Carbon::now()->addHour());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
