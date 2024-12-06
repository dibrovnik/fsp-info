<?php namespace Legacyteam\FspInfo\Models;

use Model;
use File;
/**
 * Model
 */
class Region extends Model
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
    public $table = 'legacyteam_fspinfo_regions';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'district_id' => 'required|integer|exists:legacyteam_fspinfo_districts,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'code' => 'nullable|string|max:50',
        'is_verificated' => 'boolean',
    ];

    public $jsonable = ['contacts']; // Поле для работы с JSON

    public $belongsTo = [
        'district' => ['Legacyteam\FspInfo\Models\District', 'key' => 'district_id'], // Связь с районом
    ];

    public $hasMany = [
        'agents' => ['Legacyteam\FspInfo\Models\Agent', 'key' => 'region_id'], // Связь с агентами
        'news' => ['Legacyteam\FspInfo\Models\News', 'key' => 'region_id'], // Связь с новостями
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    // public static function createOrUpdateRegion(array $data, $regionId = null)
    // {
    //     // Если передан $regionId, ищем существующую запись
    //     $region = $regionId ? static::find($regionId) : new static();

    //     // Если запись для обновления не найдена, бросаем исключение
    //     if ($regionId && !$region) {
    //         throw new \Exception("Регион с ID {$regionId} не найден.");
    //     }

    //     $rules = (new static)->rules;
    //     $validation = \Validator::make($data, $rules);

    //     if ($validation->fails()) {
    //         throw new \ValidationException($validation);
    //     }

    //     // Обновляем или создаём запись
    //     $region->district_id = $data['district_id'];
    //     $region->name = $data['name'];
    //     $region->description = $data['description'] ?? $region->description;
    //     $region->code = $data['code'] ?? $region->code;
    //     $region->is_verificated = $data['is_verificated'] ?? false;

    //     // Сохраняем запись
    //     $region->save();

    //     return $region;
    // }
    public static function createOrUpdateRegion(array $data, $regionId = null, $file = null)
    {
        // Если передан $regionId, ищем существующую запись
        $region = $regionId ? static::find($regionId) : new static();

        // Если запись для обновления не найдена, бросаем исключение
        if ($regionId && !$region) {
            throw new \Exception("Регион с ID {$regionId} не найден.");
        }

        $rules = (new static)->rules;
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        // Обновляем или создаём запись
        $region->district_id = $data['district_id'] ?? $region->district_id;
        $region->name = $data['name'];
        $region->description = $data['description'] ?? $region->description;
        $region->code = $data['code'] ?? $region->code;
        $region->is_verificated = $data['is_verificated'] ?? false;

        // Сохраняем контакты как JSON
        // Очистка пустых контактов
        $contacts = array_filter($data['contacts'] ?? [], function ($contact) {
            return !empty($contact['title']) && !empty($contact['value']);
        });
        $region->contacts = array_values($contacts); // Сбрасываем ключи массива
        // Сохраняем запись
        $region->save();

        // Загружаем аватар, если передан файл
        if ($file) {
            $region->uploadAvatar($file);
        }

        return $region;
    }

    public function uploadAvatar($file)
    {
        if (!$file) {
            return;
        }

        // Удаляем старый аватар, если он существует
        if ($this->avatar) {
            $this->avatar->delete();
        }

        // Добавляем новый аватар
        $this->avatar()->create(['data' => $file]);
    }


    public static function getRegion($region_id)
    {
         return Region::where('id', $region_id)->first();
    }

    public function district()
    {
        return $this->belongsTo(District::class); // Каждый регион принадлежит одному округу
    }


}
