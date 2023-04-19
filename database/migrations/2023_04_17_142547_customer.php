<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('source')->default(0);
            $table->string('response')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('province')->nullable();
            $table->longText('description')->nullable();
            $table->longText('note')->nullable();
            $table->integer('start_time')->nullable();
            $table->integer('created_time')->nullable();
            $table->unsignedBigInteger('admin_id')->default(0);
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
        Schema::dropIfExists('customer');
    }
}
