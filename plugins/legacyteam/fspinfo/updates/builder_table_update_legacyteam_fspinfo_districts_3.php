<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoDistricts3 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_districts', function($table)
        {
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_districts', function($table)
        {
            $table->dropColumn('description');
        });
    }
}
