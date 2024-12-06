<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoAgents2 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->integer('role')->unsigned()->default(0);
            $table->dropColumn('role_id');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->dropColumn('role');
            $table->integer('role_id')->unsigned();
        });
    }
}
