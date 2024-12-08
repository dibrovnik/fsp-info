<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\Region;
use Legacyteam\FspInfo\Models\District;
use Illuminate\Http\JsonResponse;


class RegionsApiController extends ApiController
{
    public static function getRegions()
    {
        return District::with('regions')->whereNot('id', 11)->get(); // Получаем все округа с регионами
    }
    public static function getRegionsRaw()
    {
        return Region::withCount('events')->whereNot('id', 1)->get();
    }

    public static function getRegionInfo($id)
    {
        return Region::where('id', $id)->with(['district', 'avatar', 'banner'])->first();
    }

    public static function regionsApi(): JsonResponse
    {
        $url = "https://fsp-russia.com/region/regions/";

        // Инициализация cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        curl_close($ch);

        // Проверяем успешность запроса
        if (!$html) {
            return response()->json(['error' => 'Не удалось загрузить страницу.'], 500);
        }

        // Создаем объект DOMDocument
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // Игнорируем ошибки парсинга HTML
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $accordionItems = $xpath->query("//div[contains(@class, 'accordion-item')]");
        $data = [];

        foreach ($accordionItems as $item) {
            $headerNode = $xpath->query(".//div[contains(@class, 'accordion-header')]/h4", $item)->item(0);
            $key = $headerNode ? trim($headerNode->textContent) : "Без названия";

            $contactNodes = $xpath->query(".//div[contains(@class, 'accordion-content')]//div[contains(@class, 'contact_td')]", $item);

            $regions = [];
            foreach ($contactNodes as $contactNode) {
                $subjectNode = $xpath->query(".//div[contains(@class, 'con')]//p[contains(@class, 'white_region')]", $contactNode)->item(0);
                $contactNodeNode = $xpath->query(".//div[contains(@class, 'con')]//p[contains(@class, 'white_region')]", $contactNode)->item(2);
                $subject = $subjectNode ? trim($subjectNode->textContent) : null;
                $contact = $contactNodeNode ? trim($contactNodeNode->textContent) : null;

                $regions[] = [
                    'subject' => $subject,
                    'contact' => $contact,
                ];
            }

            $data[$key] = $regions;
        }

        foreach ($data as $districtName => $regions) {
            $district = District::whereRaw('LOWER(name) = ?', [mb_strtolower($districtName)])->first();
            if ($district) {
                foreach ($regions as $region) {
                    $existingRegion = Region::where('name', $region['subject'])->where('district_id', $district->id)->first();
                    if (!$existingRegion) {
                        Region::create([
                            'district_id' => $district->id,
                            'name' => $region['subject'],
                            'contacts' => $region['contact'] ?? null,
                            'is_actual' => 0,
                        ]);
                    }
                }
            }
        }

        return response()->json(District::with('regions')->get());
    }



}