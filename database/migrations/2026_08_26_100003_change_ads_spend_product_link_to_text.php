<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeAdsSpendProductLinkToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `ads_spend` MODIFY `product_link` TEXT NULL');
        DB::statement('ALTER TABLE `ads_spend` MODIFY `note` TEXT NULL');
        DB::statement('ALTER TABLE `ads_budget` MODIFY `note` TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `ads_spend` MODIFY `product_link` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `ads_spend` MODIFY `note` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `ads_budget` MODIFY `note` VARCHAR(255) NULL');
    }
}
