<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoResults extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_results', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->string('winner_name');
            $table->string('contact_info')->nullable();
            $table->integer('position')->nullable();
            $table->integer('score')->nullable();
            $table->foreign('event_id')->references('id')->on('legacyteam_fspinfo_events')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_results', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
        });
        Schema::dropIfExists('legacyteam_fspinfo_results');
    }
}
