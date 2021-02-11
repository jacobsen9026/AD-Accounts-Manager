<?php


namespace app\controllers\settings;


use App\App\App;
use App\Controllers\Controller;
use System\App\AppException;

class SettingsController extends Controller
{
    public function __construct(App $app)
    {

        parent::__construct($app);
        if (!$this->user->superAdmin) {

            return $this->unauthorized();
            //throw new AppException('You\'ve entered a forbidden area.', AppException::UNAUTHORIZED_ACCESS);
        }
    }


}