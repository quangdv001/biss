<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('field')->nullable();
            $table->longText('description')->nullable();
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('planer_id')->nullable();
            $table->unsignedBigInteger('executive_id')->nullable();
            $table->string('package')->nullable();
            $table->string('payment_month')->nullable();
            $table->string('fanpage')->nullable();
            $table->string('website')->nullable();
            $table->string('extra_link')->nullable();
            $table->integer('accept_time')->nullable();
            $table->integer('expired_time')->nullable();
            $table->integer('created_time')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('project');
    }
}
