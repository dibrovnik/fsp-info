<?php namespace Legacyteam\Fspinfo\Components;

use Cms\Classes\ComponentBase;


/**
 * EditRegionComponent Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class RedirectComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'RedirectComponent',
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
    
        return redirect()->to('https://dev-level.ru/');
    }
}
