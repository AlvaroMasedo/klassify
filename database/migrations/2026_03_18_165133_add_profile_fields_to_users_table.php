<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name');
            $table->string('nickname')->unique()->after('surname');
            $table->string('role')->after('password');
            $table->string('teacher_status')->nullable()->after('role');
            $table->string('specialization')->nullable()->after('teacher_status');
            $table->boolean('is_private')->default(false)->after('specialization');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'nickname',
                'role',
                'teacher_status',
                'specialization',
                'is_private',
            ]);
        });
    }
};