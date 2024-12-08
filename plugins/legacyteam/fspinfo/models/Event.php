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
        // 'name' => 'required|string|max:255',
        // 'description' => 'required|string',
        // 'date_from' => 'required|date',
        // 'date_to' => 'required|date|after_or_equal:date_from',
    ];

    public $belongsTo = [
        'agent' => ['Legacyteam\FspInfo\Models\Agent', 'key' => 'agent_id'], 
    ];

    public $hasMany = [
        'comments' => ['Legacyteam\FspInfo\Models\Comment', 'key' => 'event_id'], 
        'results' => ['Legacyteam\FspInfo\Models\Result', 'key' => 'event_id'],
    ];

    public $attachMany = [
        'photos' => \System\Models\File::class,
        'files' => \System\Models\File::class,
    ];

    public $attachOne = [
        'attachment' => \System\Models\File::class,
        'photo' => \System\Models\File::class,
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
    public static function createOrUpdateEvent(array $data, $file = null, $eventId = null, $photo = null)
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
        $event->name = $data['name'] ?? $event->name;
        $event->description = $data['description'] ?? $event->description;
        $event->agent_id = $data['agent_id'] ?? $event->agent_id; // Сохраняем старое значение, если не передано новое
        $event->date_from = $data['date_from'] ?? $event->date_from;
        $event->date_to = $data['date_to'] ?? $event->date_to; 
        if(!isset($data['address'])){
            $event->address = $event->address;
        } else{
            $event->address = !isset($data['is_online']) ? $data['address'] : null;
        }
        
        $event->priority = $data['priority'] ?? $event->priority;
        if(!$eventId){
            if(isset($data['priority']) && $data['priority'] < 2){
                $event->status = 1;
            }
            else{
                $event->status = 0;
            }
        } else{
            $event->status = $data['status'] ?? $event->status;
        }
        $event->participants = $data['participants'] ?? $event->participants;
        
        
        if(isset($event->status) && isset($data['status'])){
            $statuses = [
                0 => 'В обработке',
                1 => 'Подтвержден',
                2 => 'Отклонен',
                3 => 'Требует действия',
            ];
            $statusLabel = $statuses[$data['status']];
            $eventURL = url('/event/preview/' . $event->id);
            $agent = $event->agent;
            $agent->sendNotification("Изменен статус заявки по мероприятию <a href='{$eventURL}'>{$event->name}</a> на {$statusLabel}");
        }

        $event->save();

        if($eventId == null){
            $eventURL = url('/event/preview/' . $event->id);
            $agent = $event->agent;
            $agent->sendNotification("Создана новая заявка на мероприятие: <a href='{$eventURL}'>{$event->name}</a>");
            Agent::sendNotificationToGeneral("Создана новая заявка на мероприятие: <a href='{$eventURL}'>{$event->name}</a>");
        }
        else{
            $eventURL = url('/event/preview/' . $event->id);
            Agent::sendNotificationToGeneral("В заявке: <a href='{$eventURL}'>{$event->name}</a> внесены изменения");
        }

        // Обрабатываем файл положения, если он есть
        if ($file) {
            $event->uploadFile($file);
        }
        // Обрабатываем файл положения, если он есть
        if ($photo) {
            $event->uploadPhoto($photo);
        }

      

        return $event;
    }

        /**
     * Загрузка файла положения
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function uploadFile($file)
    {
        if (!$file) {
            return;
        }

        // Удаляем старый файл, если он существует
        if ($this->attachment) {
            $this->attachment->delete();
        }

        // Привязываем новый файл
        $this->attachment()->create(['data' => $file]);
    }

    public function uploadPhoto($photo)
    {
        if (!$photo) {
            return;
        }

        // Удаляем старый файл, если он существует
        if ($this->photo) {
            $this->photo->delete();
        }

        // Привязываем новый файл
        $this->photo()->create(['data' => $photo]);
    }

    public static function getAllEvents()
    {
        return Event::all();
    }
    public static function getPhotoPath()
    {
        return $this->photo->getPath();
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
    /**
     * Accessor для статуса
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            0 => 'В обработке',
            1 => 'Подтвержден',
            2 => 'Отклонен',
            3 => 'Требует действия',
        ];

        return $statuses[$this->status] ?? 'Не указан'; // Возвращаем значение или "Не указан" по умолчанию
    }
}
