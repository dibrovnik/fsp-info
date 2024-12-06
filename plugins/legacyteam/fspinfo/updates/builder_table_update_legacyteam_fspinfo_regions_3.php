<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoRegions3 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->boolean('is_verificated')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->dropColumn('is_verificated');
        });
    }
}
