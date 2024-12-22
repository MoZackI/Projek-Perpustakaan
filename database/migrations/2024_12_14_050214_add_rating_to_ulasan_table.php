<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->integer('rating')->default(0); // Nilai default 0
        });
    }

    public function down()
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
