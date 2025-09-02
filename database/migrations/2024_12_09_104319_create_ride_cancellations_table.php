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
        Schema::create('ride_cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->unsignedBigInteger('cancel_id')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints (if required)
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('ride_id')->references('id')->on('ride_requests')->onDelete('set null');
            $table->foreign('cancel_id')->references('id')->on('cancellation_reasons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_cancellations');
    }
};
