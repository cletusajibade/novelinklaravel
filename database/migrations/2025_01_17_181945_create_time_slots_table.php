<?php

use App\Models\Client;
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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id('slot_id'); // Primary key
            $table->double('duration');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->boolean('blocked')->default(false); // Can be controlled by admin e.g blocking a time slot by setting this flag to 1.
            $table->enum('status', ['available','booked', 'canceled'])->default('available');
            $table->unsignedBigInteger('action_by')->nullable(); // Client who took an action on the slot
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('action_by')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
