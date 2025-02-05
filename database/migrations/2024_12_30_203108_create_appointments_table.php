<?php

use App\Models\Client;
use App\Models\Payment;
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
            $table->foreignIdFor(Client::class); // Foreign key to Clients table
            $table->foreignIdFor(Payment::class);// Foreign key to Payments table
            $table->timestamp('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->integer('duration')->nullable(); // Duration of the appointment (in minutes)
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            $table->string('location')->nullable(); // Location of the appointment, eg Zoom.
            $table->text('notes')->nullable();
            $table->timestamp('reminder_at')->nullable(); // Reminder notification time
            $table->text('cancellation_reason')->nullable(); // Reason for cancellation (if canceled)
            $table->string('unique_token')->nullable()->unique();
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
