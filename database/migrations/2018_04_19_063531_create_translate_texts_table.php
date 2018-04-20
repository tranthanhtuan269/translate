<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslateTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translate_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source', 255);
            $table->string('trans', 255)->nullable();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('language_id');
            $table->tinyInteger('translate_type')->default(0);
            $table->string('slug', 255);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();

            $table->index(['category_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translate_texts');
    }
}
