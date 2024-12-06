<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoAgents extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->integer('verification_status')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->dropColumn('verification_status');
        });
    }
}
