<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('opportunities', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('stage');
            }

            if (!Schema::hasColumn('opportunities', 'closed_by')) {
                $table->foreignId('closed_by')->nullable()->after('closed_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('opportunities', 'final_amount')) {
                $table->decimal('final_amount', 10, 2)->nullable()->after('closed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'closed_by')) {
                $table->dropConstrainedForeignId('closed_by');
            }

            if (Schema::hasColumn('opportunities', 'closed_at')) {
                $table->dropColumn('closed_at');
            }

            if (Schema::hasColumn('opportunities', 'final_amount')) {
                $table->dropColumn('final_amount');
            }
        });
    }
};