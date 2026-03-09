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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('uid')->unique()->nullable();
            $table->string('instapay_link')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('theme_preference')->default('light');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone_number', 'uid', 'instapay_link', 'whatsapp', 'theme_preference']);
        });
    }
};
