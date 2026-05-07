<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('commercial_status_check_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('ticket_id');
        $table->unsignedBigInteger('customer_id');
        $table->unsignedBigInteger('checked_by');
        $table->timestamp('checked_at');
        $table->string('status');
        $table->string('stage_result')->nullable();
        $table->string('opportunity_reference')->nullable();
        $table->text('error_message')->nullable();
        $table->timestamps();
    });
}

       public function down(): void
    {
        Schema::dropIfExists('commercial_status_check_logs');
    }
};