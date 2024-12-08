<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\News;
use Legacyteam\FspInfo\Models\Agent;

class NewsApiController extends ApiController
{
    public static function getAllNews()
    {
        return News::with('photo')->get();
    }
    public static function getNewsByRegion($id)
    {
        return News::where('region_id', $id)->with('photo')->get();
    }
    public static function getNew($new_id)
    {
        return News::where('id', $new_id)->with('photo')->first(); 
    }
}