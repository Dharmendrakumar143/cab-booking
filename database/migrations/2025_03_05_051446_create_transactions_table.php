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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_time')->nullable();
            $table->enum('status', ['unpaid','pending', 'paid', 'failed'])->default('unpaid');
            $table->integer('ordering')->nullable();
            $table->timestamps(); 
            $table->softDeletes();

            // Foreign key constraints (if required)
            $table->foreign('ride_id')->references('id')->on('ride_requests')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
