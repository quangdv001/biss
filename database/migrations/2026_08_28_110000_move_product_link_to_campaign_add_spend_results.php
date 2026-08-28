<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveProductLinkToCampaignAddSpendResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_campaign', function (Blueprint $table) {
            $table->text('product_link')->nullable()->after('channel');
        });

        Schema::table('ads_spend', function (Blueprint $table) {
            $table->integer('results')->nullable()->default(0)->after('amount');
        });

        DB::table('ads_spend')
            ->whereNotNull('product_link')
            ->orderBy('spend_date')
            ->get(['campaign_id', 'product_link'])
            ->groupBy('campaign_id')
            ->each(function ($rows, $campaignId) {
                DB::table('ads_campaign')->where('id', $campaignId)
                    ->update(['product_link' => $rows->last()->product_link]);
            });

        Schema::table('ads_spend', function (Blueprint $table) {
            $table->dropColumn('product_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_spend', function (Blueprint $table) {
            $table->text('product_link')->nullable()->after('amount');
        });

        Schema::table('ads_spend', function (Blueprint $table) {
            $table->dropColumn('results');
        });

        Schema::table('ads_campaign', function (Blueprint $table) {
            $table->dropColumn('product_link');
        });
    }
}
