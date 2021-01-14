<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_collections', function (Blueprint $table) {
            $table->id();
            $table->string('collection_text')->nullable();
            $table->longText('collection_description')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('page_title')->nullable();
            $table->string('collection_image_file_name_source')->nullable();
            $table->string('collection_image_file_name_two')->nullable();
            $table->string('page_name')->nullable();
            $table->boolean('status')->default(0);
            $table->string('collection_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_collections');
    }
}
