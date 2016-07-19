<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;


class DefaultController extends AbstractActionController
{

	public function getView()
	{
		$this->layout()->setVariables(array(
    			"identity"=>$this->getIdentity(),
		));
		$view = new ViewModel();
    	return $view;
   	}

	public function getIdentity()
    {
		$auth = new AuthenticationService();
   		$sm = $this->getServiceLocator();
		$sm->setService('Zend\Authentication\AuthenticationService', $auth);
    	return $auth->getIdentity();
    }
	public function getAuth()
    {
		$auth = new AuthenticationService();
   		$sm = $this->getServiceLocator();
		$sm->setService('Zend\Authentication\AuthenticationService', $auth);
    	return $auth ;
    }
	
	// @return: 1 Sucess
	// 			0 Failure
	public function authenticate($username, $password)
    {
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$config = $sm->get('Config');
		$staticSalt = $config['static_salt'];

    	$auth= $this->getAuth();
		$authAdapter = new AuthAdapter($dbAdapter,
						   'users', // there is a method setTableName to do the same
						   'username', // there is a method setIdentityColumn to do the same
						   'password', // there is a method setCredentialColumn to do the same
						   'MD5(?)' // setCredentialTreatment(parametrized string) 'MD5(?)'
						  );
		$authAdapter
			->setIdentity($username)
			->setCredential($password);
		;
		$result = $auth->authenticate($authAdapter);			
		switch ($result->getCode()) {
			case Result::FAILURE_IDENTITY_NOT_FOUND:
				// do stuff for nonexistent identity
				break;
			case Result::FAILURE_CREDENTIAL_INVALID:
				// do stuff for invalid credential
				break;
			case Result::SUCCESS:
				$storage = $auth->getStorage();
				$storage->write($authAdapter->getResultRowObject(
					null,
					'username'
				));
				return 1;
				$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
		// if ($data['rememberme']) $storage->getSession()->getManager()->rememberMe($time); // no way to get the session
				// if ($data['rememberme']) {
				// 	$sessionManager = new \Zend\Session\SessionManager();
				// 	$sessionManager->rememberMe($time);
				// }
				break;
			default:
				// do stuff for other failure
				break;
		}	
		return 0 ;

    }

}
