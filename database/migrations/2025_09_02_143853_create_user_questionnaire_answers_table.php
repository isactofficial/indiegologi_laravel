<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('question_key');
            $table->text('answer_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_questionnaire_answers');
    }
};