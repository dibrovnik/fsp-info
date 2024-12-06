<?php namespace Legacyteam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Region;
use Legacyteam\FspInfo\Models\Agent;
use Legacyteam\FspInfo\Models\District;
use Input;
use Log;
use Flash;
use Carbon;
use Request;
use Auth;

/**
 * EditRegionComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class EditRegionComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Edit Region Component Component',
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
        $region_id = $this->param('id');
        $this->page['region'] = Region::getRegion($region_id);

        $this->page['allDistricts'] = District::getAllDistricts();
    }

    

    public function onEditRegion()
    {


        $data = post(); 

        try {
            $region = Region::createOrUpdateRegion($data, $data['region_id']);
            Flash::success('Регион успешно сохранён.');
        } catch (ValidationException $e) {
            Flash::error($e->getMessage());
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Flash::error('Произошла ошибка при сохранении региона: ' . $e->getMessage());
            Log::error('Произошла ошибка при сохранении региона: ' . $e->getMessage());
        }
    }

}
