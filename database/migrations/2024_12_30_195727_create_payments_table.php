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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class); // Foreign key to Client table
            $table->string('payment_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->string('stripe_customer_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('payment_method_type')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last4')->nullable();
            $table->string('description')->nullable();
            $table->string('refund_status')->nullable();
            $table->decimal('refund_amount', 15, 2)->nullable();
            $table->string('dispute_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
