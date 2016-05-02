<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('shorthand')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('homepage');
            $table->string('rss_feed')->nullable();
            $table->boolean('active')->default(1);
            $table->string('author')->nullable();
            $table->string('author_twitter')->nullable();
            $table->string('author_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sources');
    }
}
