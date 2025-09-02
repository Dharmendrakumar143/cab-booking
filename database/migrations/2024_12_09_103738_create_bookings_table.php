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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->date('booking_date'); 
            $table->string('booking_time'); 
            $table->decimal('ride_booking_amount', 10, 2);
            $table->decimal('final_booking_amount', 10, 2)->nullable();
            $table->string('distance'); 
            $table->string('duration'); 
            $table->enum('payment_method', ['stripe', 'cash'])->default('stripe'); 
            $table->enum('booking_status', ['Confirmed','In Progress','Pending','Cancelled','Completed','Failed','Rejected','Paused'])->default('Pending');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relationships
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('set null'); 
            $table->foreign('ride_id')->references('id')->on('ride_requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
