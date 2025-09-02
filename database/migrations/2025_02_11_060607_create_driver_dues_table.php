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
        Schema::create('driver_dues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('ride_id');
            $table->string('total_due');
            $table->timestamp('last_payment')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending'); 
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('ride_id')->references('id')->on('ride_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_dues');
    }
};
