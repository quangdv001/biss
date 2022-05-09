<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('input')->nullable();
            $table->longText('output')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_time');
            $table->integer('qty');
            $table->integer('priority');
            $table->integer('deadline_time')->nullable();
            $table->integer('complete_time')->nullable();
            $table->unsignedBigInteger('admin_id_c');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('phase_id');
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
        Schema::dropIfExists('ticket');
    }
}
