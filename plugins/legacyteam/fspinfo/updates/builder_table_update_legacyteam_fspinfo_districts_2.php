<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoDistricts2 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_districts', function($table)
        {
            $table->string('short_name')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_districts', function($table)
        {
            $table->dropColumn('short_name');
        });
    }
}
