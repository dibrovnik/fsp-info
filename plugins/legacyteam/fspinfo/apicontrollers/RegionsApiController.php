<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\Region;
use Legacyteam\FspInfo\Models\District;

class RegionsApiController extends ApiController
{
    public static function getRegions()
    {
        return District::with('regions')->get(); // Получаем все округа с регионами
    }

}