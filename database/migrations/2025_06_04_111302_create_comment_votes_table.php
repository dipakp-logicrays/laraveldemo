<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();

            // Ensure one vote per user per comment
            $table->unique(['comment_id', 'user_id']);

            // Indexes for performance
            $table->index(['comment_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_votes');
    }
};
