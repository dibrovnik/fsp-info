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
use Files;
use System\Models\File;
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

    public function onUploadAvatar()
    {

        $region_id = $this->param('id');
        $region = Region::getRegion($region_id);

        $avatarFile = Input::file('avatar');

        if (!$avatarFile) {
            Flash::error('Файл не найден. Пожалуйста, выберите файл для загрузки.');
            return;
        }

        // Логирование для отладки
        Log::info("Загружаемый файл аватара: " . $avatarFile->getClientOriginalName());

        if (!$region) {
            Flash::error('Регион не найден.');
            return;
        }

        // Создание объекта файла
        $file = new File();
        $file->data = $avatarFile;
        $file->is_public = true;
        $file->save();

        // Привязка файла к аватару региона
        $region->avatar()->add($file);

        Flash::success('Аватар успешно загружен.');
    }

    public function onUploadBanner()
    {
        $region_id = $this->param('id');
        $region = Region::getRegion($region_id);

        if (!$region) {
            Flash::error('Регион не найден.');
            return;
        }

        // Получение файла баннера
        $bannerFile = Input::file('banner');
        if (!$bannerFile) {
            Flash::error('Файл не найден. Пожалуйста, выберите файл для загрузки.');
            return;
        }

        // Создание объекта файла и сохранение
        $file = new \System\Models\File();
        $file->data = $bannerFile;
        $file->is_public = true;
        $file->save();

        // Привязка файла к баннеру региона
        $region->banner()->add($file);

        Flash::success('Баннер успешно обновлён.');
    }

}
