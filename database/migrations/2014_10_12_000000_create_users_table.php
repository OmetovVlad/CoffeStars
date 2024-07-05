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
            $table->bigInteger('id')->unique();
            // $table->bigInteger('tg_id')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('sur_name')->nullable();
            $table->string('username')->nullable();
            $table->string('language_code');
            $table->boolean('is_premium')->default(false);
            $table->string('card')->nullable();
            $table->unsignedBigInteger('invited')->nullable();
            // $table->foreign('invited')->references('id')->on('users');
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
