<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoAgents3 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->text('contacts')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_agents', function($table)
        {
            $table->dropColumn('contacts');
        });
    }
}
