<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('organizer_id')->constrained('organizers')->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('sub_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_event_id')->constrained('sub_events')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('currency')->default('IDR');
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('tax', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('IDR');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->boolean('paid')->default(false);
            $table->boolean('verified')->default(false);
            $table->string('transaction_id')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('supports');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('sub_events');
        Schema::dropIfExists('events');
    }
};
