<?php namespace LegacyTeam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Agent;
use Log;
use Flash;
use Input;
use Carbon;
use Request;
use Auth;

/**
 * EditEventComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class EditEventComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Edit Event Component Component',
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
        $event_id = $this->param('id');
        $event = Event::getEvent($event_id );

        if (!$agent) {
            Flash::error('Страница доступна только представителям');
            return redirect()->to('/');
        }

        // if ($event->agent_id == $agent->id) {
        //     Flash::error('Заявка доступна только представителю который ее создал');
        //     return redirect()->to('/');
        // }
        
        $this->loadEventData($event_id);
    }
    
    public function loadEventData($event_id)
    {
        $this->page['event'] = Event::getEvent($event_id);
    }

    public function onEditEvent()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());

        $event_data = post('event_id');
        $data = post(); // Получение данных из формы
        $file = Input::file('position-files'); // Получение файла из формы
        $photo = Input::file('photo'); // Получение файла из формы

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
            $event = Event::createOrUpdateEvent($data, $file, $event_data, $photo);
            Log::info('Мероприятие успешно обновлено');
            Flash::success('Мероприятие успешно обновлено');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при обновлении мероприятия: ' . $e->getMessage());
            Log::error('Произошла ошибка при обновлении мероприятия: ' . $e->getMessage());
        }
    }
}
