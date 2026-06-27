<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('schedule_subtopic_id')->nullable()->after('schedule_procedure_id')->constrained('schedule_procedure_subtopics')->nullOnDelete();
            $table->foreignId('procedure_subtopic_id')->nullable()->after('schedule_subtopic_id')->constrained('procedure_subtopics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_subtopic_id');
            $table->dropConstrainedForeignId('procedure_subtopic_id');
        });
    }
};
