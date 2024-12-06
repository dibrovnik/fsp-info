<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\News;
use Legacyteam\FspInfo\Models\Agent;

class NewsApiController extends ApiController
{
    public static function getAllNews()
    {
        return News::get();
    }
    public static function getNewsByRegion($id)
    {
        return News::where('region_id', $id)->get();
    }
}