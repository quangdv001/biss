<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsBudgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_budget', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->bigInteger('amount')->default(0);
            $table->string('note')->nullable();
            $table->unsignedInteger('entered_time')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('campaign_id')->references('id')->on('ads_campaign')
                ->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admin')
                ->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admin')
                ->onDelete('set null');
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
        Schema::dropIfExists('ads_budget');
    }
}
