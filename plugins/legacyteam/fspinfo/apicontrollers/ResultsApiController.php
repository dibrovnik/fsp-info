<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\Result;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Agent;

use Shuchkin\SimpleXLSX;




use Log;


class ResultsApiController extends ApiController
{
    
    public static function getResultsByRegion($id)
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

    public static function getResultsByEvent($id)
    {
        return Result::where('event_id', $id)->get();
    }


    public static function setResults()
    {
        if (request()->hasFile('results')) {
            $file = request()->file('results');
            
            if ($file->isValid()) {
                $xlsx = SimpleXLSX::parse($file->getPathname());

                if ($xlsx) {
                    $rows = $xlsx->rows();

                    foreach ($rows as $index => $row) {
                        if ($index === 0) continue;

                        $resultData = [
                            'event_id' => $row[0] ?? null,
                            'winner_name' => $row[1] ?? null,
                            'contact_info' => isset($row[2]) ? (string)$row[2] : null,
                            'position' => $row[3] ?? null,
                            'score' => $row[4] ?? null,
                        ];

                        Result::createOrUpdateResult($resultData);
                    }

                    $filePath = $file->store('uploads');
                    return response()->json([
                        'success' => true,
                        'message' => 'Файл успешно загружен.',
                        'filePath' => $filePath
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ошибка при парсинге Excel файла.'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Файл повреждён.'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Файл не был найден.'
        ]);
    }

}