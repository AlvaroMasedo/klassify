<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();

            $table->integer('recipient_id');
            $table->integer('actor_id');
            $table->integer('resource_id');
            $table->integer('comment_id')->nullable();

            $table->enum('type', ['like', 'comment']);

            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index(['recipient_id', 'read_at']);
            $table->index(['actor_id']);
            $table->index(['resource_id']);
            $table->index(['comment_id']);

            $table->foreign('recipient_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('actor_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->cascadeOnDelete();

            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};