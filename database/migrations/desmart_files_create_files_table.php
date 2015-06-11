<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DesmartFilesCreateFilesTable extends Migration
{

    public function up()
    {
        Schema::create('files', function ($table) {
            $table->increments('id');
            $table->string('name')
                ->nullable();
            $table->string('path');
            $table->integer('size')
                ->unsigned()
                ->default(0);
            $table->string('md5_checksum', 32);
            $table->timestamp('created_at');

            $table->primary('id');
        });

        Schema::create('file_records', function ($table) {
            $table->integer('file_id')
                ->unsigned();
            $table->integer('file_record_id')
                ->unsigned();
            $table->string('file_record_type');
            $table->boolean('is_default')
                ->default(0);
            $table->json('data')
                ->nullable();
            $table->timestamps();

            $table->foreign('file_id')
                ->references('files')
                ->on('id')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_records');
        Schema::dropIfExists('files');
    }
}
