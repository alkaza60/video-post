<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->integer('duration');
            $table->integer('size');
            $table->enum('status', ['processing', 'ready', 'error'])->default('processing');
            $table->unsignedBigInteger('user_id'); // Adicionar coluna user_id
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
            // Adicionar relacionamento de chave estrangeira para a tabela users, com cascading delete
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
