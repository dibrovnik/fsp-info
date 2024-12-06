<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoEvents3 extends Migration
{
    public function up()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->date('date_from')->nullable()->unsigned(false)->default(null)->comment(null)->change();
            $table->date('date_to')->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_events', function($table)
        {
            $table->dateTime('date_from')->nullable()->unsigned(false)->default(null)->comment(null)->change();
            $table->dateTime('date_to')->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
}
