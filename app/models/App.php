<?php
class App{
	
	var $config;
	
	
	function __construct(){
		$this->setConfig(new Config());
	}
	
	public function run(){	
		
	
		
		
		
		//Show header
		include("./app/header.php");


		//Show content
		include("./app/viewController.php");


		//Show footer
		include("./app/footer.php");

	}
	
	function getConfig(){
		
		return $config;
		
	}
	
	function setConfig($inputConfig){
		$config = $inputConfig;
		
		
	}
	
}


?>