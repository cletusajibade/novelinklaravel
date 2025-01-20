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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('first_name', length: 32);
            $table->string('last_name', length: 32);
            $table->string('email', length: 64)->unique();
            $table->string('phone', length: 32);
            $table->date('date_of_birth');
            $table->string('country', length: 64);
            $table->string('country_of_residence', length: 64);
            $table->string('country_residence_status', length: 24)->nullable();
            $table->string('marital_status', length: 16)->nullable();
            $table->boolean('have_a_passport')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_country', length: 64)->nullable();
            $table->boolean('family_coming_to_canada')->nullable();
            $table->string('highest_education', length: 48)->nullable();
            $table->string('occupation', length: 64)->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->boolean('canadian_work_experience')->nullable();
            $table->boolean('canadian_education')->nullable();
            $table->boolean('refused_visa')->nullable();
            $table->boolean('involved_in_genocide')->nullable();
            $table->boolean('criminal_offence')->nullable();
            $table->text('background_check_details')->nullable();
            $table->text('other_information')->nullable();
            $table->json('consultation_package')->nullable();
            $table->string('registration_status', length: 64)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
