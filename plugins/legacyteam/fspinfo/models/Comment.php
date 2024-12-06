<?php namespace Legacyteam\FspInfo\Models;

use Model;

/**
 * Model
 */
class Comment extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_comments';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsTo = [
        'sender' => ['Legacyteam\FspInfo\Models\Agent', 'key' => 'sender_id'], // Связь с отправителем (пользователь)
        'event' => ['Legacyteam\FspInfo\Models\Event', 'key' => 'event_id'], // Связь с событием
    ];

}
