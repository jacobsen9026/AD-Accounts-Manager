<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		
		//echo "test:";
		//echo $this->user->username;
		//echo parent::getCSS();
		echo view('welcome_message');
		
		//$this->show();
	}

	//--------------------------------------------------------------------

}
