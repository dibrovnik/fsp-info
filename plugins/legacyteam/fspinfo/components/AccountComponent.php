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
 * AccountComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class AccountComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'AccountComponent Component',
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
        $this->page['agent'] = $agent;
        $this->page['events'] = json_encode($this->getAllEventsByRegion($agent->region->id));
        // $this->page['results'] = json_encode($this->getResultsByRegion($agent->region->id));
        $this->page['results'] = json_encode($this->getResultsByRegion($agent->region->id));
    }

    public function getAllEventsByRegion($id)
    {
        $agents = Agent::where('region_id', $id)->with('events')->get();

        $events = [];

        foreach ($agents as $agent) {
            foreach ($agent->events as $event) {
                $events[] = $event;
            }
        }

        return $events;
    }
    
    public function getResultsByRegion($id)
    {
        $agents = Agent::where('region_id', $id)
            ->with(['events.results'])
            ->get();

        $results = [];

        foreach ($agents as $agent) {
            foreach ($agent->events as $event) {
                $results = array_merge($results, $event->results->toArray());
            }
        }

        return $results;
    }
}
