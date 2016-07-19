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


class AuthController extends DefaultController
{
	public function indexAction()
    {
		return $this->getView(); 
    }

    public function registerAction()
    {
    	$req = $this->getRequest();
        if($req->isPost()){
			$config = $this->getServiceLocator()->get('Config');
			$conn = mysql_connect($config['db']['host'],$config['db']['username'] , $config['db']['password']);
			mysql_select_db( "brainer" ) or die( 'Error'. mysql_error() );

            $post = $req->getPost();
            if(isset($post->login)){
   				$username= $post->login['username']; 
   				$password= $post->login['password']; 
   				$data= $post->login;
   				// check -> login -> redirect ;
				if( $this->authenticate($username, $password)){
					$this->redirect()->toRoute('application',array('controller'=>'index', 'action'=>'register'),true);
					// $this->redirect('home');
				}
				else{
					// try again..
				}


   				
            }
   			else if(isset($post->register)){
   				$username= $post->register['username']; 
   				$password= $post->register['password']; 
   				$firstname= $post->register['firstname']; 
   				$lastname= $post->register['lastname']; 
   				$email= $post->register['email']; 

				$result = mysql_query("SELECT * FROM users WHERE username='$username' OR email='$email' ") or die(mysql_errno());
				if( mysql_num_rows($result) >0 ){
					echo "Username or Email address is not available" ;
					return new ViewModel();
				}
				else{
					$md5 = MD5($password) ;
					$result = mysql_query("INSERT INTO users (`username` , `password`, `firstname`, `lastname`, `email`) VALUES ('$username' , '$md5', '$firstname', '$lastname', '$email') ") or die(mysql_errno());
					// login and redirect
					$res = $this->authenticate($username, $password); 

				}
   			}
   			return $this->redirect('home');
        }

    	return $this->getView();
    }


    public function logoutAction()
	{
		$auth = $this->getAuth();
		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
		}			
		
		$auth->clearIdentity();
		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();
		
		return $this->redirect()->toRoute('auth', array('controller' => 'auth', 'action' => 'register'));		
	}	
}
