<?php
namespace Rest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel as JsonModel;
use Zend\Session\Container; // We need this when using sessions

class TagController extends AbstractRestfulController
{
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
		parent::onDispatch($e);
	}

	public function Create($data){
		date_default_timezone_set("UTC"); 

		$config = $this->getServiceLocator()->get('Config');
		$conn = mysql_connect($config['db']['host'],$config['db']['username'] , $config['db']['password']);
		mysql_select_db( "brainer" ) or die( 'Error'. mysql_error() );

		$tags = array();

		$task = $data['task'] ; 
		if( $task == 'add_new_tag' ){
			$new_tag = mysql_real_escape_string($data['new_tag']);
			$time = time(); 
			$result = mysql_query("INSERT INTO `tags` (`name`, `date`) VALUES ('$new_tag', '$time')")  or die(mysql_error());

			$rows = mysql_query("SELECT * FROM `tags` WHERE deleted!=1 ")  or die(mysql_error());
			while($res = mysql_fetch_assoc($rows)) {
			    $tags[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $tags
				)
			);
		}
		else if( $task == 'get_all_tags' ){
			$rows = mysql_query("SELECT * FROM `tags` WHERE deleted!=1 ")  or die(mysql_error());
			while($res = mysql_fetch_assoc($rows)) {
			    $tags[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $tags
				)
			);
		}
		else if( $task == 'delete_selected_tags' ){
			foreach ($data['selected_tags'] as $x ) {
				$tag_id = $x['id'] ; 
				$rows = mysql_query("UPDATE `tags` SET `deleted`=1  WHERE id='$tag_id' ")  or die(mysql_error());
			}

			$rows = mysql_query("SELECT * FROM `tags` WHERE deleted!=1 ")  or die(mysql_error());

			while($res = mysql_fetch_assoc($rows)) {
			    $tags[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $tags
				)
			);
		}
	}

	public function getList(){
		$tags = array();

			$rows = mysql_query("SELECT * FROM `tags` ")  or die(mysql_error());
			while($res = mysql_fetch_assoc($rows)) {
			    $tags[] = $res;
			}
			return (new JsonModel (
				array(
					'list' => $tags
				)
			));
	}

}