<?php namespace LegacyTeam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Agent;
use Log;
use Flash;
use Input;
use Carbon;
use Auth;

/**
 * EventComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class CreateEventComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Event Component Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if (!Auth::check()) {
            return redirect()->to('/account/login');
        }

        $agent = Agent::getAgentByUser(Auth::getUser());
        if (!$agent) {
            Flash::error('Страница доступна только представителям');
            return redirect()->to('/');
        }
    }

    public function onCreateEvent()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());

        $data = post(); // Получение данных из формы
        $file = Input::file('position-files'); // Получение файла из формы
        Log::info($data);

        try {
            $data['agent_id'] = $agent->id;
            // Разделяем данные из daterange
            if (!empty($data['daterange'])) {
                $dates = explode(' - ', $data['daterange']); // Разделяем диапазон
                $data['date_from'] = \Carbon\Carbon::createFromFormat('d.m.Y', $dates[0])->format('Y-m-d'); // Преобразуем в формат YYYY-MM-DD
                Log::info('date_from ' . $data['date_from']);
                $data['date_to'] = \Carbon\Carbon::createFromFormat('d.m.Y', $dates[1])->format('Y-m-d');   // Преобразуем в формат YYYY-MM-DD
                Log::info('date_to ' . $data['date_to']);
            }

            // Создаём мероприятие
            $event = Event::createOrUpdateEvent($data, $file);
            Log::info('Мероприятие успешно создано');
            Flash::success('Мероприятие успешно создано');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при создании мероприятия: ' . $e->getMessage());
            Log::error('Произошла ошибка при создании мероприятия: ' . $e->getMessage());
        }
    }
}
