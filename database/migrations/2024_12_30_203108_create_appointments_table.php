<?php

use App\Models\Consultation;
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
            $table->foreignIdFor(Consultation::class); // Foreign key to Consultations table
            $table->timestamp('appointment_date');
            $table->integer('duration')->nullable(); // Duration of the appointment (in minutes)
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available');
            $table->string('location')->nullable(); // Location of the appointment, eg Zoom.
            $table->text('notes')->nullable();
            $table->timestamp('reminder_at')->nullable(); // Reminder notification time
            $table->text('cancellation_reason')->nullable(); // Reason for cancellation (if canceled)
            $table->string('payment_status')->nullable(); // should come from Payments table
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
