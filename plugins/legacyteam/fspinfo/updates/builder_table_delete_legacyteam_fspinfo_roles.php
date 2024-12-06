<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteLegacyteamFspinfoRoles extends Migration
{
    public function up()
    {
        Schema::dropIfExists('legacyteam_fspinfo_roles');
    }
    
    public function down()
    {
        Schema::create('legacyteam_fspinfo_roles', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 255);
        });
    }
}
