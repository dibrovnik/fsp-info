<?php namespace Legacyteam\FspInfo\Models;

use Model;
use File;
use ValidationException;
use Log;


/**
 * Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_events';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
    ];

    public $belongsTo = [
        'agent' => ['Legacyteam\FspInfo\Models\Agent', 'key' => 'agent_id'], // Связь с агентом
    ];

    public $hasMany = [
        'comments' => ['Legacyteam\FspInfo\Models\Comment', 'key' => 'event_id'], // Связь с комментариями
        'results' => ['Legacyteam\FspInfo\Models\Result', 'key' => 'event_id'],
    ];

    public $attachMany = [
        'photos' => \System\Models\File::class,
        'files' => \System\Models\File::class,
    ];

    /**
     * @var bool timestamps are enabled.
     */
    public $timestamps = true;

    /**
     * Создание нового мероприятия
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return self
     * @throws ValidationException
     */
    public static function createOrUpdateEvent(array $data, $file = null, $eventId = null)
    {
        // Если передан $eventId, ищем существующее мероприятие
        $event = $eventId ? static::find($eventId) : new static();

        // Если мероприятие для обновления не найдено, бросаем исключение
        if ($eventId && !$event) {
            throw new \Exception("Мероприятие с ID {$eventId} не найдено.");
        }

        // Валидируем данные
        $rules = (new static)->rules;
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        // Обновляем или создаём мероприятие
        $event->name = $data['name'];
        $event->description = $data['description'];
        $event->agent_id = $data['agent_id'] ?? $event->agent_id; // Сохраняем старое значение, если не передано новое
        $event->short_description = $data['short-description'] ?? $event->short_description;
        $event->date_from = $data['date_from'];
        $event->date_to = $data['date_to'];
        $event->address = !isset($data['is_online']) ? $data['address'] : null;
        $event->priority = $data['priority'];

        $event->save();

        // Обрабатываем файл положения, если он есть
        if ($file) {
            // Если это обновление, удаляем старые файлы перед добавлением новых
            if ($eventId) {
                $event->files()->delete();
            }
            $event->files()->create(['data' => $file]);
        }

        return $event;
    }


    public static function getAllEvents()
    {
        return Event::all();
    }
    public static function getAcceptedEvents()
    {
        return Event::where('status', 1)->get();
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public static function getNoAcceptedEvents()
    {
        return Event::where('status', 0)->get();
    }

    public static function getEvent($event_id)
    {
        return Event::where('id', $event_id)->first();
    }

    /**
     * Accessor для приоритета
     */
    public function getPriorityLabelAttribute()
    {
        $priorities = [
            0 => 'Не указан',
            1 => 'Муниципальный',
            2 => 'Региональный',
            3 => 'Федеральный',
            4 => 'Всероссийский',
        ];

        return $priorities[$this->priority] ?? 'Не указан'; // Возвращаем значение или "Не указан" по умолчанию
    }
}
