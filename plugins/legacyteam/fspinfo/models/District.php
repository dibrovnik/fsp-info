<?php namespace Legacyteam\FspInfo\Models;

use Model;

/**
 * Model
 */
class District extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_districts';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $hasMany = [
        'regions' => ['Legacyteam\FspInfo\Models\Region', 'key' => 'district_id'], // Связь с регионами
    ];

    public static function getAllDistricts()
    {
        return District::pluck('id','name')->all();
    }
    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
