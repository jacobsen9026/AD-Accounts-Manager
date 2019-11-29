<?php namespace App\Models;
use App\Models\User;

class Auth
{
	
	public $authType;
	public $authValid;
	public $authPermissionGroup;
	private $localAPI;
	private $appConfig;
	
	
	
	
	
	function __construct($config){
		$this->session = \Config\Services::session();
		$this->appConfig = $config;
		$this->localAPI = new \App\Models\AuthAPI\LocalAuthAPI ($config);
		//loadUser();
		
	}
	
	public function attemptAuthorization($user, $password=null){
		//echo $user->username;
		$this->authValid=false;
		if($user->username=="admin"){
			$this->authValid=true;
		}
		//var_export($this);
		return $this;
	}
	
	
}