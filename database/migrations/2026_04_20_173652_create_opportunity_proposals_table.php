<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('opportunity_proposals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('file_path');
    $table->string('file_name');
    $table->timestamp('uploaded_at');
    $table->unsignedBigInteger('uploaded_by');
    $table->string('status')->default('Borrador');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_proposals');
    }
};