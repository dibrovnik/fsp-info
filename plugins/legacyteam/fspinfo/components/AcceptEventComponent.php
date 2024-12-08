<?php namespace LegacyTeam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Agent;
use Legacyteam\FspInfo\Models\Comment;
use Log;
use Flash;
use Input;
use Carbon;
use Request;
use Auth;

/**
 * AcceptEventComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class AcceptEventComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Accept Event Component Component',
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

        $currentUrl = Request::path();
        
        if($currentUrl == 'event/accept-events')
        {
            $this->loadEventsData();
        } else {
            $event_id = $this->param('id');
            $this->loadEventData($event_id);
        }
        
    }
    
    public function loadEventsData()
    {
        $this->page['events'] = Event::getNoAcceptedEvents();
    }

    public function loadEventData($event_id)
    {
        $this->page['event'] = Event::getEvent($event_id);
    }

    public function onSendComment()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());
        $event_id = $this->param('id');

        $data = post(); // Получение данных из формы

        try {
            $data['event_id'] = $event_id;
            $data['sender_id'] = $agent->id;

            // Создаём мероприятие
            $comment = Comment::createOrUpdateComment($data);
            Log::info('Сообщение успешно создано');
            Flash::success('Сообщение успешно создано');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при отправки сообщения: ' . $e->getMessage());
            Log::error('Произошла ошибка при отправки сообщения: ' . $e->getMessage());
        }
    }

    public function onAccept()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());
        $event_id = $this->param('id');
        $data = []; 
       
        try {
            // $data['agent_id'] = $agent->id;
            $data['status'] = 1;
            $event = Event::createOrUpdateEvent($data, null, $event_id);
            Flash::success('Мероприятие успешно подтверждено');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при обновлении мероприятия: ' . $e->getMessage());
        }
    }

    public function onSentToEdit()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());
        $event_id = $this->param('id');
        $data = []; 
       
        try {
            // $data['agent_id'] = $agent->id;
            $data['status'] = 3;
            $event = Event::createOrUpdateEvent($data, null, $event_id);
            Flash::success('Мероприятие успешно обновленно');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при обновлении мероприятия: ' . $e->getMessage());
        }
    }

    public function onRejectt()
    {
        $agent = Agent::getAgentByUser(Auth::getUser());
        $event_id = $this->param('id');
        $data = []; 
       
        try {
            // $data['agent_id'] = $agent->id;
            $data['status'] = 2;
            $event = Event::createOrUpdateEvent($data, null, $event_id);
            Flash::success('Мероприятие успешно обновленно');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при обновлении мероприятия: ' . $e->getMessage());
        }
    }
}
