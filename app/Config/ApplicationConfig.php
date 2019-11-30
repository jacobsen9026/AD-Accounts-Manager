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
	public $webMOTD = 'This is the MOTD';
	protected $webAdminPassword;
	
	
	public $adminUsernames;
	public $adminEmails;
	
	
	public $welcomeEmailRecipients;
	
	public $welcomeEmail;
	
	
	public $psDomainController;
	public $psDomainFQDN;
	public $psDomainNetBIOS;
	
	public $smtpServerFQDN;
	public $smtpServerPort;
	
	
	public $gamTechDriveEmailGroup;
	public $gamParentGroups;
	public $gamStaffGroups;
	public $gamStudentGroups;
	public $gamDomainName;
	
	public $emailFromAddress;
	public $emailFromName;
	public $emailToAddress;
	
	function __construct(){
		$this->webAdminPassword = hash("sha256","admin");
	}
	

}
