<?php namespace Legacyteam\FspInfo\Models;

use Model;
use File;
/**
 * Model
 */
class Agent extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_agents';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => ['RainLab\User\Models\User', 'key' => 'user_id'], // Связь с пользователем
        'region' => ['Legacyteam\FspInfo\Models\Region', 'key' => 'region_id'], // Связь с регионом
    ];

    public $hasMany = [
        'events' => ['Legacyteam\FspInfo\Models\Enent', 'key' => 'agent_id'], // Связь с событиями
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    public static function getAgentByUser($user){
        return Agent::where('user_id', $user->id)->first();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
