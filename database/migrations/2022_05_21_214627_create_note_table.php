<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note', function (Blueprint $table) {
            $table->id();
            $table->longText('note');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('phase_id');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('group_id')->references('id')->on('group')
                ->onDelete('cascade');
            $table->foreign('phase_id')->references('id')->on('phase')
                ->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin')
                ->onDelete('cascade');
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
        Schema::dropIfExists('note');
    }
}
