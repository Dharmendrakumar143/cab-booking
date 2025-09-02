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
        Schema::create('ride_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('pick_up_address');
            $table->string('pick_up_city')->nullable();
            $table->string('pick_up_state')->nullable();
            $table->string('pick_up_country')->nullable();
            $table->decimal('pick_up_latitude', 10, 7); 
            $table->decimal('pick_up_longitude', 10, 7); 
            $table->date('pick_up_date'); 
            $table->string('pick_up_time'); 
            $table->string('drop_off_address'); 
            $table->string('drop_off_city')->nullable();
            $table->string('drop_off_state')->nullable();
            $table->string('drop_off_country')->nullable();
            $table->decimal('drop_off_latitude', 10, 7); 
            $table->decimal('drop_off_longitude', 10, 7); 
            $table->date('drop_off_date')->nullable();
            $table->string('drop_off_time')->nullable();
            $table->enum('ride_status', ['requested', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->integer('total_passenger');
            $table->string('person_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('ordering')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relationship with the users table
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
