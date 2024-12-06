<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoEvents extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_events', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('agent_id')->unsigned();
            $table->integer('status')->default(0);
            $table->dateTime('date_from')->nullable();
            $table->dateTime('date_to')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('legacyteam_fspinfo_events');
    }
}
