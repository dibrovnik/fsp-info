<?php namespace Legacyteam\FspInfo\Models;

use Model;
use File;
use GuzzleHttp\Client;

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
        'events' => ['Legacyteam\FspInfo\Models\Event', 'key' => 'agent_id'], // Связь с событиями
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    public static function allEventsPage( $agent)
    {
        return Event::where('agent_id', $agent->id)->get();
    }

    public static function getAgentByUser($user){
        return Agent::where('user_id', $user->id)->first();
    }

    public static function getEvents($agent){
        return Event::where('agent_id', $agent->id)->get();
    }

    /**
     * Accessor для приоритета
     */
    public function getRole()
    {
        $roles = [
            0 => 'Региональный представитель',
            1 => 'Федеральный представитель',
        ];

        return $roles[$this->role] ?? 'Не указан'; // Возвращаем значение или "Не указан" по умолчанию
    }

    /**
     * Создание нового агента
     *
     * @param array $data Входные данные для создания агента
     * @return self
     * @throws ValidationException
     */
    public static function createAgent(array $data)
    {
        // Определяем правила валидации
        $rules = [
            'user_id' => 'required|exists:users,id', // Проверяем, что пользователь существует
            'region_id' => 'required|exists:legacyteam_fspinfo_regions,id', // Проверяем, что регион существует
            'verification_status' => 'boolean', // Статус верификации (0 или 1)
            'role' => 'required|integer|in:0,1', // Роль: 0 или 1
            'contacts' => 'nullable|string|max:255', // JSON-строка контактов
            'post' => 'nullable|string|max:255', // Должность
        ];

        // Выполняем валидацию данных
        $validation = \Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        // Создаём нового агента
        $agent = new static();
        $agent->user_id = $data['user_id'];
        $agent->region_id = $data['region_id'];
        $agent->verification_status = $data['verification_status'] ?? 0;
        $agent->role = $data['role'];
        $agent->contacts = $data['contacts'] ?? null; // Контакты (JSON-строка)
        $agent->post = $data['post'] ?? null; // Должность
        $agent->save();

        return $agent;
    }

    /**
     * Отправляет уведомление через POST-запрос.
     *
     * @param string $message Текст сообщения
     * @return bool Успешность отправки
     */
    public function sendNotification($message)
    {
        $url = 'http://95.163.243.245:8000/send-notification/';
        $client = new Client();

        try {
            $response = $client->post($url, [
                'json' => [
                    'agent_id' => $this->id,
                    'message' => $message,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Ошибка отправки уведомления: ' . $e->getMessage());
            return false;
        }

        return false;
    }

    /**
     * Отправляет уведомление всем агентам с ролью 1
     *
     * @param string $message Текст сообщения
     */
    public static function sendNotificationToGeneral($message)
    {
        $agents = self::where('role', 1)->get();

        foreach ($agents as $agent) {
            $agent->sendNotification("Центральный представитель: ".$message);
        }
    }

}
