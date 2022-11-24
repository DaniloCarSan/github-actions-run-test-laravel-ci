<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('PERMISSION_CODE');
            $table->string('PERMISSION_NAME');
            $table->string('PERMISSION_SLUG');
            $table->enum('PERMISSION_ACTIVE',['Y','N'])->default('Y');
            $table->string('PERMISSION_DESCRIPTION')->nullable();
            $table->text('PERMISSION_ABILITY')->unique();
            $table->foreignId('PERMISSION_PERMISSION_CODE')->nullable()->constrained('permissions','PERMISSION_CODE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
