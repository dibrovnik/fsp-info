<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoRegions extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_regions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('legacyteam_fspinfo_regions');
    }
}
