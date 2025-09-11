<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParagraphsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table already exists before creating
        if (!Schema::hasTable('paragraphs')) {
            Schema::create('paragraphs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subheading_id')->constrained()->onDelete('cascade');
                $table->text('content');
                $table->integer('order_number')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paragraphs');
    }
}