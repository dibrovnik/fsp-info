<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoRegions4 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->text('contacts')->nullable();
            $table->boolean('is_actual')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_regions', function($table)
        {
            $table->dropColumn('contacts');
            $table->dropColumn('is_actual');
        });
    }
}
