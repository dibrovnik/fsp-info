<?php namespace Legacyteam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Result;
use Legacyteam\FspInfo\Models\Agent;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Log;
use Input;
use Flash;
use ValidationException;

class CreateResultComponent extends ComponentBase
{
    public $events;

    public function componentDetails()
    {
        return [
            'name' => 'Create Result Component',
            'description' => 'Компонент для создания и редактирования результатов'
        ];
    }

    public function onRun()
    {
        $this->loadAgentEvents();
    }

    private function loadAgentEvents()
    {
        $user = Auth::getUser();

        if (!$user) {
            Flash::error('Вы должны быть авторизованы.');
            return redirect('/login');
        }

        $agent = Agent::getAgentByUser(Auth::getUser());
        if (!$agent) {
            Flash::error('Только агенты могут добавлять результаты.');
            return redirect('/');
        }

        $this->events = Event::where('agent_id', $agent->id)->get();
    }

    public function onCreateResult()
    {
        $data = post();

        try {
            $result = Result::createOrUpdateResult($data);
            Flash::success('Результат успешно сохранён.');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка: ' . $e->getMessage());
        }
    }
}
