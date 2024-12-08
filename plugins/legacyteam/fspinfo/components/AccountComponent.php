<?php namespace Legacyteam\Fspinfo\Components;

use Cms\Classes\ComponentBase;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Region;
use Legacyteam\FspInfo\Models\Agent;
use Legacyteam\FspInfo\Models\District;
use Illuminate\Support\Facades\Storage;
use Input;
use RainLab\User\Models\User;
use Log;
use Flash;
use Carbon;
use Request;
use DB;
use Http;
use Mail;
use File;
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

        // Получаем выбранный регион из GET запроса
        $selectedRegionId = input('region_id');
        Log::info("selectedRegionId " . $selectedRegionId);

        if ($agent->role == 1) {
            $this->page['allEvents'] = Event::all();
            $this->page['allRegions'] = Region::whereNot('id', 1)->get();

            if ($selectedRegionId) {
                $this->page['eventsForAnalitics'] = json_encode($this->getAllEventsByRegion($selectedRegionId));
                $this->page['resultsForAnalitics'] = json_encode($this->getResultsByRegion($selectedRegionId));
            } else {
                $this->page['eventsForAnalitics'] = json_encode($this->getAllEventsByRegion(15));
                $this->page['resultsForAnalitics'] = json_encode($this->getResultsByRegion(15));
            }
        } else {
            $this->page['allEvents'] = $agent->events;
            $this->page['allRegions'] = [$agent->region];
            $this->page['eventsForAnalitics'] = json_encode($this->getAllEventsByRegion($agent->region->id));
            $this->page['resultsForAnalitics'] = json_encode($this->getResultsByRegion($agent->region->id));
        }


        $this->page['eventsForReports'] = Event::where('agent_id', $agent->id)->get();
        $this->page['allRegionsForSelect'] = Region::getAllRegions();
        $this->page['selectedRegionId'] = $selectedRegionId ?? 15; // Передаём выбранный регион в шаблон
        $this->page['districts'] = District::all();  // Получаем все округа


    }

public function onCreateEventReport()
{
    $eventId = post('event_id');

    if (!$eventId) {
        Flash::error('Необходимо выбрать мероприятие.');
        return;
    }

    try {
        // Сгенерировать PDF
        $response = Http::get("http://localhost:5002/generate_event_pdf/" . $eventId);

        if ($response->successful()) {
            // Содержимое PDF
            $fileContent = $response->body();

            // Формируем уникальное имя для файла
            $fileName = 'event_report_' . $eventId . '.pdf';

            // Путь для сохранения файла
            $filePath = storage_path('app/media/' . $fileName);
            Log::info('filePath' . $filePath);

            // Сохраняем файл
            File::put($filePath, $fileContent);

            // Генерируем URL для доступа
            $fileUrl = asset('storage/app/media/' . $fileName);

            Flash::success('Отчет успешно создан. Скачайте его по ссылке ниже.');

            return [
                '#pdfDownloadLink' => $this->renderPartial('pdf-download-link', [
                    'link' => $fileUrl
                ])
            ];
        } else {
            Flash::error('Ошибка при создании отчета: ' . $response->status());
        }
    } catch (\Exception $e) {
        Flash::error('Произошла ошибка: ' . $e->getMessage());
    }
}

public function onCreateOkrugReport()
{
    $okrugId = post('okrug_id');  // Получаем okrug_id

    if (!$okrugId) {
        Flash::error('Необходимо выбрать округ.');
        return;
    }

    try {
        // Сгенерировать PDF для округа
        $response = Http::get("http://localhost:5002/generate_okrug_pdf/" . $okrugId);

        if ($response->successful()) {
            // Содержимое PDF
            $fileContent = $response->body();

            // Формируем уникальное имя для файла
            $fileName = 'okrug_report_' . $okrugId . '.pdf';

            // Путь для сохранения файла
            $filePath = storage_path('app/media/' . $fileName);
            Log::info('filePath: ' . $filePath);

            // Сохраняем файл
            File::put($filePath, $fileContent);

            // Генерируем URL для доступа
            $fileUrl = asset('storage/app/media/' . $fileName);

            Flash::success('Отчет успешно создан. Скачайте его по ссылке ниже.');

            return [
                '#pdfDownloadLink' => $this->renderPartial('pdf-download-link', [
                    'link' => $fileUrl
                ])
            ];
        } else {
            Flash::error('Ошибка при создании отчета: ' . $response->status());
        }
    } catch (\Exception $e) {
        Flash::error('Произошла ошибка: ' . $e->getMessage());
    }
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

    public function onAddAgent()
    {
        $email = post('email');
        $firstName = post('first_name');
        $lastName = post('last_name');
        $region_id = post('region_id');

        if (User::where('email', $email)->exists()) {
            throw new \ValidationException(['email' => 'Пользователь с таким email уже существует.']);
        }

        DB::beginTransaction();

        try {
            $temporaryPassword = $this->generateSimplePassword();
            Log::info("temporaryPassword " . $temporaryPassword);
            $user = $this->createNewUser($email, $firstName, $lastName, $temporaryPassword);

            $this->createAgent($user, $region_id);

            DB::commit();

            
            Flash::success('Новый партнер успешно создан.');

            return [
                '#newAgentInfo' => $this->renderPartial('new-agent-info', [
                    'user' => $user,
                    'password' => $temporaryPassword
                ]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Flash::error('Ошибка: ' . $e->getMessage());
        }
    }

    protected function createNewUser($email, $firstName, $lastName, $password)
    {
        $user = new \RainLab\User\Models\User;
        $user->email = $email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->password = $user->password_confirmation = $password;
        $user->save();

        return $user;
    }

    protected function createAgent($user, $region_id)
    {
        $data = [
            'user_id' => $user->id,
            'region_id' => $region_id,
            'verification_status' => 0,
            'role' => 0,
        ];
        $agent = Agent::createAgent($data);
    }

    protected function generateSimplePassword($length = 8)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';

        // Генерируем минимум одну букву и одну цифру
        $password = substr(str_shuffle($letters), 0, $length - 1); // Генерируем буквы
        $password .= substr(str_shuffle($digits), 0, 1); // Добавляем хотя бы одну цифру

        // Перемешиваем пароль для большей случайности
        return str_shuffle($password);
    }


 
}
