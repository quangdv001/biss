<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noty', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type')->default(1);
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('phase_id');
            $table->unsignedBigInteger('admin_id_c');
            $table->unsignedBigInteger('admin_id');
            $table->unique(['admin_id', 'group_id', 'type']);
            $table->foreign('admin_id_c')->references('id')->on('admin')
                ->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin')
                ->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('project')
                ->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('group')
                ->onDelete('cascade');
            $table->foreign('phase_id')->references('id')->on('phase')
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
        Schema::dropIfExists('noty');
    }
}
