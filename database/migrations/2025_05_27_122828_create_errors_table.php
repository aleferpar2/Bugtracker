<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('errors', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('technology')->nullable();
        $table->string('version')->nullable();
        $table->string('source')->nullable();
        $table->enum('status', ['open', 'resolved', 'closed'])->default('open');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
    }
    
};

