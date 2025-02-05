<?php

use App\Models\Client;
use App\Models\Payment;
use App\Models\TimeSlot;
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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(Client::class)->nullable()->constrained();
            $table->foreignIdFor(Payment::class)->nullable()->constrained();
            $table->foreignIdFor(TimeSlot::class)->nullable()->constrained();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            $table->string('confirmation_no')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('reminder_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
