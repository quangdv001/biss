<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelHandlerToAdsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_campaign', function (Blueprint $table) {
            $table->string('channel')->nullable()->after('project_id');
            $table->unsignedBigInteger('handler_id')->nullable()->after('channel');
            $table->foreign('handler_id')->references('id')->on('admin')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_campaign', function (Blueprint $table) {
            $table->dropForeign(['handler_id']);
            $table->dropColumn(['channel', 'handler_id']);
        });
    }
}
