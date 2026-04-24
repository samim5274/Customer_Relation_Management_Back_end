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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('restrict');
            $table->foreignId('visa_category_id')->nullable()->constrained('visa_categories')->onDelete('restrict');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('password')->default('password');
            $table->string('photo')->nullable();

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('national_id', 50)->nullable()->unique();
            $table->string('religion', 50)->nullable();
            $table->string('occupation')->nullable();

            $table->boolean('is_active')->default(false);

            // Addresses
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();

            $table->decimal('wallet_balance', 12, 2)->default(0);

            // Submission details
            $table->boolean('is_submitted')->nullable();
            $table->string('passport_no')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_photo')->nullable();
            $table->string('national_id_photo')->nullable();

            $table->string('spouse_name')->nullable();
            $table->string('spouse_photo')->nullable();
            $table->string('spouse_nid')->nullable();
            $table->string('spouse_nid_photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
