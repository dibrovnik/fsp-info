<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoEvents2 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->string('address')->nullable();
            $table->text('short_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->dropColumn('address');
            $table->dropColumn('short_description');
        });
    }
}
