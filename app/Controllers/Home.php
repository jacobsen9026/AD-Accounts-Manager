<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		//echo parent::getCSS();
		echo view('welcome_message');
		
		//$this->show();
	}

	//--------------------------------------------------------------------

}
