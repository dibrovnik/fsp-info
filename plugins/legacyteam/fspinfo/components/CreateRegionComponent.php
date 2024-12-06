<?php namespace LegacyTeam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Region;
use Legacyteam\FspInfo\Models\Agent;
use Legacyteam\FspInfo\Models\District;
use Log;
use Flash;
use Input;
use Carbon;
use Auth;
/**
 * CreateRegionComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class CreateRegionComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Create Region Component Component',
            'description' => 'No description provided yet...'
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

        $this->page['allDistricts'] = District::getAllDistricts();
    }

    public function onCreateRegion()
    {
        $data = post();

        try {
            $region = Region::createOrUpdateRegion($data);
            Log::info('Регион успешно создан. Отправлен на верификацию');
            Flash::success('Регион успешно создан. Отправлен на верификацию');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при создании региона: ' . $e->getMessage());
            Log::error('Произошла ошибка при создании региона: ' . $e->getMessage());
        }
    }
}
