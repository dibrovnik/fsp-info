<?php namespace Legacyteam\FspInfo\Models;

use Model;

/**
 * Model
 */
class Result extends Model
{
    use \October\Rain\Database\Traits\Validation;


    public $timestamps = true;
    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_results';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsTo = [
        'event' => ['Legacyteam\FspInfo\Models\Event', 'key' => 'event_id'],
    ];

    public static function createOrUpdateResult(array $data, $resultId = null)
    {
        // Если передан $resultId, ищем существующую запись
        $result = $resultId ? static::find($resultId) : new static();

        // Если запись для обновления не найдена, бросаем исключение
        if ($resultId && !$result) {
            throw new \Exception("Результат с ID {$resultId} не найден.");
        }

        // Валидируем данные
        $rules = [
            'event_id' => 'required|integer|exists:legacyteam_fspinfo_events,id',
            'winner_name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'score' => 'nullable|integer',
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        // Обновляем или создаём запись
        $result->event_id = $data['event_id'];
        $result->winner_name = $data['winner_name'];
        $result->contact_info = $data['contact_info'] ?? null;
        $result->position = $data['position'] ?? null;
        $result->score = $data['score'] ?? null;

        // Сохраняем запись
        $result->save();

        return $result;
    }

}
