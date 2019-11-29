<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class ApplicationConfig extends BaseConfig
{
	
	/*
	|--------------------------------------------------------------------------
	| Base Site URL
	|--------------------------------------------------------------------------
	|
	| URL to your CodeIgniter root. Typically this will be your base URL,
	| WITH a trailing slash:
	|
	|	http://example.com/
	|
	| If this is not set then CodeIgniter will try guess the protocol, domain
	| and path to your installation. However, you should always configure this
	| explicitly and never rely on auto-guessing, especially in production
	| environments.
	|
	*/
	public $webApplicationName = 'testk';

	
	function __construct(){
		$this->configInterface = new \App\Middleware\ApplicationConfigInterface();
		
	}
	

}
