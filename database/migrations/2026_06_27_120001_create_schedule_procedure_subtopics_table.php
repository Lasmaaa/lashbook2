<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_procedure_subtopics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_procedure_id')->constrained()->cascadeOnDelete();
            $table->string('name_lv');
            $table->string('name_en');
            $table->string('name_ru');
            $table->decimal('price', 8, 2)->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_procedure_subtopics');
    }
};
