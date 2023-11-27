<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En el archivo de migración
    // En el archivo de migración
public function up()
{
    Schema::table('saves', function (Blueprint $table) {
        $table->string('title')->nullable();
        $table->text('description')->nullable();
    });
}

public function down()
{
    Schema::table('saves', function (Blueprint $table) {
        $table->dropColumn(['title', 'description']);
    });
}



};
