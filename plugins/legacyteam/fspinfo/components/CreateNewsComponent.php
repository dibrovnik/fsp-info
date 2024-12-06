<?php namespace Legacyteam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\News;
use Legacyteam\FspInfo\Models\Agent;
use Illuminate\Support\Facades\Input;
use ValidationException;
use Auth;


class CreateNewsComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Create News Component',
            'description' => 'Компонент для создания новостей'
        ];
    }

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

        $this->page['agent'] = $agent;
    }

    public function onCreateNews()
    {
        $data = post();
        $agent = Agent::getAgentByUser(Auth::getUser());
        $region_id = $agent->region_id;
        $data['region_id'] = $region_id;
        try {
            $news = News::createOrUpdateNews($data);
            \Flash::success('Новость успешно создана.');
        } catch (ValidationException $e) {
            \Flash::error($e->getMessage());
        } catch (\Exception $e) {
            \Flash::error('Произошла ошибка при создании новости: ' . $e->getMessage());
        }
    }
}
