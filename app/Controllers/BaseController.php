<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
class BaseController extends Controller
{
	
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['cookie','app_form','url'];
	public $appConfig;

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		
		 $this->session = \Config\Services::session();
		 //$this->request = \Config\Services::request();
		 $this->uri = \Config\Services::uri();
		 $this->config = new \Config\App();
		 //var_export($this->request->getServer());
		 $this->requestedHostname = $this->request->getServer("HTTP_HOST");
		 $this->appConfig = new \Config\ApplicationConfig();
		 $this->appConfigInterface = new \App\Middleware\ApplicationConfigInterface();
		 //$this->appConfigInterface->save($this->appConfig);
		 $this->appConfig = $this->appConfigInterface->load();
		 //$this->appConfig->webApplicationName = 'test';

		 //echo $this->appConfig->webApplicationName;
		 $this->config = config( 'App' );
		//var_export($this->uri);
		 //$this->logger->log_message('debug','Begin controlling');
		
		
		$this->user = new \App\Models\User("admin",$this->session);
		
		 $auth = service('auth');

		
		$this->user->userAuth= new \App\Models\Auth($this->appConfig);
		$this->user->userAuth->attemptAuthorization($this->user);
		$this->user->save();
		//$this->user->load();
		//var_export($this->request->getServer('REQUEST_URI'));
		
		if(!$this->isUserLoggedIn()){
			$data = [
			"formAction" => $this->request->getServer('REQUEST_URI')
			];
			//var_export($data);
			echo view('/login/prompt',$data);
			exit;
		}
		
		
		//exit;
		
	}
	
	public function before(RequestInterface $request)
{
    $auth = service('auth');

    if (! $auth->isLoggedIn())
    {
        return redirect('login');
    }
}
	
	
	private function isUserLoggedIn()
	{
		if($this->user->userAuth->authValid){
			return true;
		}
		return false;
		
	}

	
	private function render(string $name, array $data = [], array $options = [])
    {
        return view(
            'layouts',
            [
                'content' => view($name, $data, $options),
            ],
            $options
        );
    }
	
	

	
	
	

}
