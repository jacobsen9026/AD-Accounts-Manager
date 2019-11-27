<?php
class Config {
	
	var $configFileDir;
	var $configFileName;
	var $parameters;
	
	function __construct(){
		$this->load();
		$this->configFileDir = "./app/config";
		$this->configFileName = "config.json";
	}
	public function load(){
		
		if(file_exists($this->configFileDir."/".$this->configFileName)){
			$appConfig = json_decode(file_get_contents($this->configFileDir."/".$this->configFileName),true);
			$appConfig["version"] = file_get_contents("./app/version.txt");
			ksort($appConfig);
			
			$this->$parameters = $appConfig;


			
		}
	}
	
	
	
	public function save(){
		ksort($this->parameters);
		$dateTime=date("Y-m-d_H-i-s");
		if(!file_exists($this->configFileDir."/backup/")){
			mkdir($this->configFileDir."/backup/", 0740, true);
		}
		copy ($this->configFileDir."/".$this->configFileDir , $this->configFileDir."/backup/".$dateTime."_".$this->configFileName);
		file_put_contents($this->configDir."/config.json", json_encode($this->parameters));
	}
	
	
	
	
}


?>