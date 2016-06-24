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
		$tags = array();
		$new_tag = $data['new_tag'];
		$config = $this->getServiceLocator()->get('Config');
		$conn = mysql_connect($config['db']['host'],$config['db']['username'] , $config['db']['password']);
		mysql_select_db( "brainer" ) or die( 'Error'. mysql_error() );

		$result = mysql_query("INSERT INTO `tags` (`tag`) VALUES ('$new_tag')")  or die(mysql_error());

		$rows = mysql_query("SELECT * FROM `tags` ")  or die(mysql_error());
		while($res = mysql_fetch_assoc($rows)) {
		    $tags[] = $res;
		}
		return new JsonModel (
			array(
				'list' => $tags
			)
		);

		


	}

	public function getList(){
		die("getlist");

	}

}