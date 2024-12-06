<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLegacyteamFspinfoDistricts extends Migration
{
    public function up()
    {
        Schema::rename('legacyteam_fspinfo_regions', 'legacyteam_fspinfo_districts');
    }
    
    public function down()
    {
        Schema::rename('legacyteam_fspinfo_districts', 'legacyteam_fspinfo_regions');
    }
}
