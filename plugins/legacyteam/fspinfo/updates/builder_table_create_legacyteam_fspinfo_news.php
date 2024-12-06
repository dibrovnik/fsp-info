<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoNews extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_news', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->integer('region_id')->unsigned();
            
            $table->foreign('region_id')->references('id')->on('legacyteam_fspinfo_regions')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_regions', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });
        Schema::dropIfExists('legacyteam_fspinfo_news');
    }
}
