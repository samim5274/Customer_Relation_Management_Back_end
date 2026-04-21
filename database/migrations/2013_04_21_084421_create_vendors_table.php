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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Basic Shop Info
            $table->string('shop_name')->unique();           // Unique vendor/shop name
            $table->string('shop_slug')->unique();           // SEO-friendly URL slug
            $table->string('shop_logo')->nullable();         // Shop logo
            $table->string('shop_logo_2')->nullable();         // Shop logo
            $table->text('shop_description')->nullable();    // About the shop

            // Vendor Status
            $table->enum('vendor_status', ['pending','approved','suspended','rejected'])->default('pending');
            $table->boolean('is_active')->default(true);     // Active or deactivated shop

            // Financial
            $table->decimal('wallet_balance',12,2)->default(0);  // Vendor wallet / credit
            $table->decimal('commission_rate',5,2)->default(0.00); // Default platform commission %

            // Legal / Business Info
            $table->string('tax_id')->nullable();                 // VAT / Tax ID
            $table->string('business_license')->nullable();       // Business license number
            $table->string('business_document')->nullable();      // Path to uploaded doc

            // Contact Info
            $table->string('email')->nullable();                  // Vendor email
            $table->string('phone')->nullable();                  // Vendor phone
            $table->string('emergency_phone')->nullable();        // Vendor phone

            // Address Info
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();

            // Optional Settings / Features
            $table->boolean('featured')->default(false);          // Featured vendor on homepage
            $table->integer('rating')->default(0);                // Average rating (optional)
            $table->integer('total_products')->default(0);        // For quick queries
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
