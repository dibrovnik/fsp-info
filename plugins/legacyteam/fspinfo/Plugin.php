<?php namespace Legacyteam\FspInfo;

use System\Classes\PluginBase;
use Event;
use DB;
use Log;
/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        $this->registerConsoleCommand('fspinfo.importevents', \Legacyteam\FspInfo\Console\ImportEvents::class);
    }


    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        Event::listen('rainlab.user.register', function ($component, $user) {
            Log::info("OK");
            // Log::info(array_key_exists('user_type', $input));
            // if (array_key_exists('user_type', $input)) {
                Db::table('legacyteam_fspinfo_agents')->insert([
                    'user_id' => $user->id,
                    'role' => 0,
                    'region_id' => post('region_id'),
                    'verification_status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            // }
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Legacyteam\fspinfo\Components\CreateEventComponent' => 'createEvent',
            'Legacyteam\fspinfo\Components\AcceptEventComponent' => 'acceptEvent',
            'Legacyteam\fspinfo\Components\EditEventComponent' => 'editEvent',

            'Legacyteam\fspinfo\Components\AccountComponent' => 'accountComponent',
            'Legacyteam\fspinfo\Components\CreateRegionComponent' => 'createRegion',
            'Legacyteam\fspinfo\Components\EditRegionComponent' => 'editRegion',

            'Legacyteam\fspinfo\Components\CreateNewsComponent' => 'createNews',

            'Legacyteam\fspinfo\Components\CreateResultComponent' => 'createResult',
            'Legacyteam\fspinfo\Components\RedirectComponent' => 'redirectToMain',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
