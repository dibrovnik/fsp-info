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
}
