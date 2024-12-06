<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoEvents5 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->integer('participants')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->dropColumn('participants');
        });
    }
}
