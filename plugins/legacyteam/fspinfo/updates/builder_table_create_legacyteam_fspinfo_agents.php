<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoAgents extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_agents', function($table)
        {
            $table->increments('id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('region_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_agents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('legacyteam_fspinfo_agents');
    }
}
