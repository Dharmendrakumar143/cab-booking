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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone_number')->nullable();
            $table->boolean('status')->default(1);
            $table->string('profile_pic')->nullable();
            $table->boolean('is_email_verified')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_number_plate')->nullable();
            $table->enum('driver_status', ['available', 'unavailable', 'offline', 'pending_verification'])->default('pending_verification');
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'suspended'])->default('pending');

            $table->string('google_id')->nullable();
            $table->string('google_email')->nullable();
            $table->boolean('is_2fa_enabled')->default(false);
            $table->string('google_2fa_secret')->nullable();
            $table->json('google_2fa_backup_codes')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
