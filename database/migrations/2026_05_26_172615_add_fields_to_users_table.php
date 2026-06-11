<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name');
            $table->string('phone')->nullable()->after('surname');
            $table->string('loyalty_code')->unique()->nullable()->after('phone');
            $table->enum('usertype', ['user', 'admin'])->default('user')->after('loyalty_code');
            $table->string('preferred_language')->default('lv')->after('usertype');
            $table->string('avatar')->nullable()->after('preferred_language');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'surname', 'phone', 'loyalty_code', 'usertype', 'preferred_language', 'avatar']);
        });
    }
};