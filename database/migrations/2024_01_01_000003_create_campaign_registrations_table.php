<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'donated', 'cancelled'])
                  ->default('pending');
            $table->text('health_notes')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->boolean('has_donated_before')->default(false);
            $table->text('admin_notes')->nullable();       // Private notes by admin
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('donated_at')->nullable();
            $table->timestamps();

            // A donor can only register once per campaign
            $table->unique(['user_id', 'campaign_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_registrations');
    }
};
