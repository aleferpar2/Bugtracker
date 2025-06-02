<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('technology')->nullable()->after('error_code');
            $table->string('application')->nullable()->after('technology');
            $table->string('version')->nullable()->after('application');
            $table->year('year')->nullable()->after('version');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['technology', 'application', 'version', 'year']);
        });
    }
};
