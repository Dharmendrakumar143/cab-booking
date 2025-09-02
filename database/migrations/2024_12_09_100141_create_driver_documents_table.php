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
        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id'); 
            $table->unsignedBigInteger('verify_by')->nullable();
            $table->string('license')->nullable();
            $table->string('registration')->nullable(); 
            $table->string('insurance')->nullable(); 
            $table->string('ownership_tesla_model')->nullable(); 
            $table->integer('driver_rating')->check('rating >= 1 AND rating <= 5')->nullable();
            $table->enum('verification_status', ['requested', 'approved', 'rejected', 'suspended'])->default('requested');
            $table->string('message')->nullable();
            $table->timestamps();  
            $table->softDeletes(); 

            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('verify_by')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_documents');
    }
};
