<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunity_proposal_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('opportunity_proposals')->cascadeOnDelete();
            $table->string('old_status');
            $table->string('new_status');
            $table->unsignedBigInteger('changed_by');
            $table->timestamp('changed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_proposal_histories');
    }
};