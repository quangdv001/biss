<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveAdsBudgetToProject extends Migration
{
    /**
     * Ngân sách ads chuyển từ nhập theo chiến dịch sang nhập theo dự án.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_budget', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('id');
        });

        // Gán dự án cho các khoản ngân sách cũ theo dự án của chiến dịch
        DB::statement('UPDATE `ads_budget` b JOIN `ads_campaign` c ON c.id = b.campaign_id SET b.project_id = c.project_id');

        Schema::table('ads_budget', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
        });
        DB::statement('ALTER TABLE `ads_budget` MODIFY `campaign_id` BIGINT UNSIGNED NULL');

        Schema::table('ads_budget', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('ads_campaign')
                ->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('project')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_budget', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['campaign_id']);
            $table->dropColumn('project_id');
        });
        DB::statement('DELETE FROM `ads_budget` WHERE `campaign_id` IS NULL');
        DB::statement('ALTER TABLE `ads_budget` MODIFY `campaign_id` BIGINT UNSIGNED NOT NULL');

        Schema::table('ads_budget', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('ads_campaign')
                ->onDelete('cascade');
        });
    }
}
