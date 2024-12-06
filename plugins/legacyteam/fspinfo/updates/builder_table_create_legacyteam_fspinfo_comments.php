<?php namespace Legacyteam\FspInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegacyteamFspinfoComments extends Migration
{
    public function up()
    {
        Schema::create('legacyteam_fspinfo_comments', function($table)
        {
            $table->increments('id')->unsigned();
            $table->text('text');
            $table->string('subject')->nullable();
            $table->integer('sender_id')->unsigned();
            $table->integer('event_id')->unsigned();
            
            $table->foreign('sender_id')->references('id')->on('legacyteam_fspinfo_agents')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('legacyteam_fspinfo_events')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('legacyteam_fspinfo_comments', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['event_id']);
        });
        
        Schema::dropIfExists('legacyteam_fspinfo_comments');
    }
}
