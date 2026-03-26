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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->text('comment');  // Comentario de la interacción
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');  // Relación con ticket
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relación con el usuario
            $table->timestamps();  // Tiempos de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};