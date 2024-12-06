<?php namespace Legacyteam\FspInfo\ApiControllers;

use Octobro\API\Classes\ApiController;
use Legacyteam\FspInfo\Models\Event;
use Legacyteam\FspInfo\Models\Agent;

class EventsApiController extends ApiController
{

  public static function getEventsByRegion($id)
  {
      $agents = Agent::where('region_id', $id)->with('events')->get();

      $events = [];

      foreach ($agents as $agent) {
          foreach ($agent->events as $event) {
              if ($event->status !== 1) {
                continue;
              }
              $events[] = $event;
          }
      }

      return $events;
  }
  public static function getAllEventsByRegion($id)
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
  public static function getAllEvents()
  {
      return Event::where('status', 1)->get();
  }
    // public function index()
    // {
    //     $products = Product::get();

    //     return $this->respondwithCollection($products, new ProductTransformer);
    // }

    // public function show($id)
    // {
    //     $product = Product::find($id);

    //     return $this->respondwithItem($product, new ProductTransformer);
    // }
}