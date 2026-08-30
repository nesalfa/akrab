<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('consultations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('consultations', 'answered_by')) {
                $table->foreignId('answered_by')->nullable()->after('answer')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('consultations', 'answered_at')) {
                $table->timestamp('answered_at')->nullable()->after('answered_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (Schema::hasColumn('consultations', 'answered_at')) {
                $table->dropColumn('answered_at');
            }
            if (Schema::hasColumn('consultations', 'answered_by')) {
                $table->dropConstrainedForeignId('answered_by');
            }
            if (Schema::hasColumn('consultations', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
