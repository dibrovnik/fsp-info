<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoRegions2 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->integer('code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->dropColumn('code');
        });
    }
}
