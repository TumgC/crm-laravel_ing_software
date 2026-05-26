<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_satisfaction_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_satisfaction_surveys');
    }
};