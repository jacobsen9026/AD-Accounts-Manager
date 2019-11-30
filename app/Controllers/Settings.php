<?php namespace App\Controllers;

class Settings extends BaseController
{
	public function index()
	{
		//$this->load->helper('url');
		
		return view('/settings/home');
	}

	//--------------------------------------------------------------------

}
