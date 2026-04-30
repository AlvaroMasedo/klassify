<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'comment_id')) {
                $table->integer('comment_id')->nullable()->after('resource_id');
                $table->index('comment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'comment_id')) {
                $table->dropIndex(['comment_id']);
                $table->dropColumn('comment_id');
            }
        });
    }
};