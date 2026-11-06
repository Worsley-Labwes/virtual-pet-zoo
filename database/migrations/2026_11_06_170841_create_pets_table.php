<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->string('name', 20);
            $table->unsignedTinyInteger('hunger')->default(50);
            $table->unsignedTinyInteger('energy')->default(50);
            $table->string('mood', 10)->default('happy');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
