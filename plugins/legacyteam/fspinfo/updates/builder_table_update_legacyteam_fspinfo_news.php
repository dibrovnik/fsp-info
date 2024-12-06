<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoNews extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_news', function($table)
        {
            $table->text('short_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_news', function($table)
        {
            $table->dropColumn('short_description');
        });
    }
}
