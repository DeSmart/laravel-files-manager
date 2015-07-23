<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DesmartFilesCreateFilesTable extends Migration
{

    public function up()
    {
        Schema::create('files', function ($table) {
            $table->string('id', 32);
            $table->string('name')
                ->nullable();
            $table->string('path');
            $table->integer('size')
                ->unsigned()
                ->default(0);
            $table->string('md5_checksum', 32);
            $table->timestamp('created_at');

            $table->primary('id');
            $table->unique('md5_checksum');
        });

        Schema::create('file_records', function ($table) {
            $table->string('file_id', 32);
            $table->string('file_record_id', 32);
            $table->string('file_record_type');
            $table->boolean('is_default')
                ->default(0);
            $table->json('data')
                ->nullable();
            $table->timestamps();

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_records');
        Schema::dropIfExists('files');
    }
}
