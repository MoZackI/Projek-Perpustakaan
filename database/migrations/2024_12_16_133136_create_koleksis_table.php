<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_koleksis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoleksisTable extends Migration
{
    public function up()
    {
        Schema::create('koleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Menambahkan relasi ke tabel users
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');  // Menambahkan relasi ke tabel buku
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('koleksis');
    }
}
