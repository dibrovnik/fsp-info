<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoRoles extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_roles', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('legacyteam_fspinfo_roles');
    }
}
