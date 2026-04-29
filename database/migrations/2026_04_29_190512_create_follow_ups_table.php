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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict'); // who is handling

            // Core Follow-up Info
            $table->string('title')->nullable();
            $table->text('note')->nullable();

            // Status & Priority
            $table->enum('status', ['pending', 'contacted', 'interested', 'not_interested', 'closed'])
                    ->default('pending');

            $table->enum('priority', ['low', 'medium', 'high'])
                    ->default('medium');


            // Scheduling
            $table->timestamp('follow_up_date')->nullable(); // next follow-up
            $table->timestamp('last_contacted_at')->nullable();

            // Communication Type
            $table->enum('contact_type', ['call', 'whatsapp', 'email', 'meeting', 'other'])
                    ->nullable();

            // Outcome
            $table->text('outcome')->nullable();

            // Optional Business Fields
            $table->decimal('deal_amount', 12, 2)->nullable();
            $table->boolean('is_converted')->default(false);

            // Reminder
            $table->boolean('reminder_sent')->default(false);

            // Metadata
            $table->json('meta')->nullable(); // flexible data (extra info)

            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['follow_up_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
