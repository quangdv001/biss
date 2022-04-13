<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_ticket', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('ticket_id');
            $table->index('admin_id');
            $table->unique(['admin_id', 'ticket_id']);
            $table->foreign('admin_id')->references('id')->on('admin')
                ->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('ticket')
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
        Schema::dropIfExists('admin_ticket');
    }
}
