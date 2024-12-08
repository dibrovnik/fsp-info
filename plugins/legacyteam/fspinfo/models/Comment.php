<?php namespace Legacyteam\FspInfo\Models;

use Model;
use Legacyteam\FspInfo\Models\Agent;
use Legacyteam\FspInfo\Models\Event;

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
        // 'name' => 'required|string|max:255',
'sender_id' => 'required|integer',
    'event_id' => 'required|integer',
    'text' => 'required|string|max:500', // Максимальная длина текста, измените при необходимости
        // 'date_from' => 'required|date',
        // 'date_to' => 'required|date|after_or_equal:date_from',
    ];

    public $belongsTo = [
        'sender' => ['Legacyteam\FspInfo\Models\Agent', 'key' => 'sender_id'], // Связь с отправителем (пользователь)
        'event' => ['Legacyteam\FspInfo\Models\Event', 'key' => 'event_id'], // Связь с событием
    ];

    
    /**
     * Создание нового мероприятия
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return self
     * @throws ValidationException
     */
    public static function createOrUpdateComment(array $data, $commentId = null)
    {
        // Если передан $eventId, ищем существующее мероприятие
        $comment = $commentId ? static::find($commentId) : new static();

        // Если мероприятие для обновления не найдено, бросаем исключение
        if ($commentId && !$comment) {
            throw new \Exception("Сообщение с ID {$commentId} не найдено.");
        }

        // Валидируем данные
        $rules = (new static)->rules;
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        // Обновляем или создаём мероприятие
        $comment->sender_id = $data['sender_id'] ?? $event->sender_id;
        $comment->event_id = $data['event_id'] ?? $event->event_id;
        $comment->text = $data['text'];

        $comment->save();
        $event = Event::where('id', $comment->event_id)->first();
        $eventURL = url('/event/preview/' . $event->id);
        $agent = Agent::where('id', $event->agent_id)->first();
        $agent->sendNotification("Новое сообщение в <a href='{$eventURL}'>{$event->name}</a>: {$comment->text}");

        return $comment;
    }
}
