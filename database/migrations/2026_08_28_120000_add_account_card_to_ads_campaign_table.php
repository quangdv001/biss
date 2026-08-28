<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountCardToAdsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_campaign', function (Blueprint $table) {
            $table->string('ad_account')->nullable()->after('handler_id');
            $table->string('payment_card')->nullable()->after('ad_account');
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
            $table->dropColumn(['ad_account', 'payment_card']);
        });
    }
}
