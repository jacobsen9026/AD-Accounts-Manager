<?php namespace App\Models;


class User
{
	
	public $username;
	public $firstName;
	public $lastName;
	public $userAuth;
	
	
	
	
	function __construct($name){
		$this->session = \Config\Services::session();
		$this->username=$name;
		//loadUser();
		
	}
	
	public function load(){

		if($this->session->get('user')){
			$saved = unserialize($this->session->get('user'));
			$this->username = $saved->username;
			$this->firstName = $saved->firstName;
			$this->lastName = $saved->lastName;
			$this->userAuth = $saved->userAuth;
			//var_export ($this->username);
			
		}else{
			$this->username="admin";
			$this->save();
		}
		//var_export($this->session->get('user'));
		
		return false;
	}
	
	public function save(){
		$this->session->set('user',serialize($this));
		//var_export($_SESSION["user"]);
		
	}
}