<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_times', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->timestamps();

            $table->unique(['date', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_times');
    }
};
