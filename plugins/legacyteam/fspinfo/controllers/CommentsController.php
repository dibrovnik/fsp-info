<?php namespace Legacyteam\FspInfo\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class CommentsController extends Controller
{
    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
    }

}
