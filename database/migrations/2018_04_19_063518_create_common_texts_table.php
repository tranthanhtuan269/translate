<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source', 255);
            $table->string('trans', 255)->nullable();
            $table->unsignedInteger('language_id');
            $table->tinyInteger('translate_type')->default(0);
            $table->string('slug', 255);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();

            $table->index(['language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_texts');
    }
}
