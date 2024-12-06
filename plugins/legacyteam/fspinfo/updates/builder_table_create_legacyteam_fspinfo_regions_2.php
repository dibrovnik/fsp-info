<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoRegions2 extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_regions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('district_id')->unsigned();
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->foreign('district_id')->references('id')->on('legacyteam_fspinfo_districts')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_agents', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
        });
        Schema::dropIfExists('legacyteam_fspinfo_regions');
    }
}
