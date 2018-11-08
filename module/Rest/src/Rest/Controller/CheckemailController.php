<?php
namespace Rest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel as JsonModel;
use Zend\Session\Container; // We need this when using sessions

class TagController extends AbstractRestfulController
{
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
		// die(var_dump($e->getRouteMatch()->getMatchedRouteName()));
		parent::onDispatch($e);
	}

	public function Create($data){

	}

	public function getList(){
		$config = $this->getServiceLocator()->get('Config');
		$conn = mysql_connect($config['db']['host'],$config['db']['username'] , $config['db']['password']);
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		$result = $conn->query('INSERT INTO tags (tag) VALUE ("test");');

		var_dump($conn);
		die("Rest getlist()");
	}

}