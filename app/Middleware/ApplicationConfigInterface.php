<?php namespace App\Middleware;

use CodeIgniter\View\View;

class ApplicationConfigInterface{
	
    private $filePath;
	private $config;
	private $backupPath;

	function __construct(){
		$this->config = new \Config\App();
		$this->filePath = ROOTPATH.$this->config->appConfigPath;
		$this->backupPath = ROOTPATH.$this->config->appBackupPath;
	}

	function save(\Config\ApplicationConfig $config){
		file_put_contents($this->filePath,serialize($config));
		
	}
	
	function load(){
		//$file = file_get_contents($this->filePath);
		//$unserialized = unserialize($file);
		//$loaded = $unserialized;
		//var_export($loaded);
		//var_export($loaded);
		//return $loaded;
		
	}
	
	function makeBackup(){
		
	}

} 