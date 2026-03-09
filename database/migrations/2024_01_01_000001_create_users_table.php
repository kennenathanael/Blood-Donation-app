<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['donor', 'admin'])->default('donor');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->foreignId('blood_group_id')
                  ->nullable()
                  ->constrained('blood_groups')
                  ->nullOnDelete();
            $table->boolean('is_eligible')->default(true);
            $table->text('medical_conditions')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('total_donations')->default(0);
            $table->timestamp('last_donation_date')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
