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
        Schema::create('redeem_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('total');
            $table->string('order_id')->charset('utf8')->collation('utf8_unicode_ci')->nullable(false);
            $table->unsignedBigInteger('admin_id')->nullable(false);
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('admin_id')->on('admins')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeem_histories');
    }
};
