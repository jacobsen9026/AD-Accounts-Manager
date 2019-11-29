<?php namespace App\Models\AuthAPI;


class LocalAuthAPI
{
	
	public $credentials;
	
	
	
	
	function __construct($config){
		$this->appConfig = $config;
		//loadUser();
		
	}
	
	public function attemptAuthorization(User $user, $password=null){
		$this->authValid=false;
		if($user->username="admin"){
			$this->authValid=true;
		}
		//var_export($this);
		return $this;
	}
	
	
}