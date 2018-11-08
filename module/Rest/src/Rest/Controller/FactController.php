<?php
namespace Rest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel as JsonModel;
use Zend\Session\Container; // We need this when using sessions

class FactController extends AbstractRestfulController
{
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
		// die(var_dump($e->getRouteMatch()->getMatchedRouteName()));
		parent::onDispatch($e);
	}

	public function Create($data){
		date_default_timezone_set("UTC"); 

		$config = $this->getServiceLocator()->get('Config');
		$conn = mysql_connect($config['db']['host'],$config['db']['username'] , $config['db']['password']);
		mysql_select_db( "brainer" ) or die( 'Error'. mysql_error() );

		$facts = array();

		$task = $data['task'] ; 
		if( $task == 'add_new_fact' ){
			$new_fact = mysql_real_escape_string($data['new_fact']);

			// $result = mysql_query("SELECT * FROM facts WHERE name='$new_fact' ") or die(mysql_errno());

			// if( mysql_num_rows($result) >0 ){
			// 	return new JsonModel (
			// 		array(
			// 			'list' => {'msg' : 'This story already exists!'}
			// 		)
			// 	);
			// }

			$time = time(); 
			$result = mysql_query("INSERT INTO `facts` (`name`, `date`) VALUES ('$new_fact', '$time')")  or die(mysql_error());
			$fact_id = mysql_insert_id(); 

			$selected_tags_ids = array(); 
			foreach ($data['selected_tags'] as $x ) {
				$selected_tags_ids[] = $x['id'];
				$tag_id = $x['id'] ; 
				$result = mysql_query("INSERT INTO `fact_in_tag` (`fact_id` , `tag_id`) VALUES ('$fact_id' , '$tag_id' )")  or die(mysql_error());
				
			}
			// var_dump($selected_tags_ids); 
			$rows= null ; 

			if(count($selected_tags_ids ) == 0){
				$rows = mysql_query("SELECT * FROM `facts`  WHERE deleted!=1 ")  or die(mysql_error());
			}
			else{
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f join `fact_in_tag` as c on f.id=c.fact_id WHERE f.deleted!=1 AND c.tag_id IN (" . implode(',', $selected_tags_ids) . ")  group by f.name")  or die(mysql_error());

			}

			while($res = mysql_fetch_assoc($rows)) {
			    $facts[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $facts
				)
			);
		}
		else if( $task == 'get_all_facts' ){

			$selected_tags_ids = array(); 
			foreach ($data['selected_tags'] as $x ) {
				$selected_tags_ids[] = $x['id'];
			}
			$rows= null ; 
			
			if(count($selected_tags_ids ) == 0){
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f WHERE f.deleted!=1 ")  or die(mysql_error());
			}
			else{
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f join `fact_in_tag` as c on f.id=c.fact_id WHERE f.deleted!=1 AND c.tag_id IN (" . implode(',', $selected_tags_ids) . ") group by f.name")  or die(mysql_error());
			}

			while($res = mysql_fetch_assoc($rows)) {
			    $facts[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $facts
				)
			);

		}
		// ##
		// ## Unlink to all tags = DELETE :)
		// ##
		else if( $task == 'delete_selected_facts' ){

			$selected_tags_ids = array(); 
			foreach ($data['selected_tags'] as $x ) {
				$selected_tags_ids[] = $x['id'];
			}
			$selected_facts_ids = array(); 
			foreach ($data['selected_facts'] as $x ) {
				$selected_facts_ids[] = $x['id'];
			}

			$rows = mysql_query("UPDATE `facts` SET `deleted`=1  WHERE id in (" . implode(',', $selected_facts_ids) . ")")  or die(mysql_error());

			// get all facts with selected tags 

			$rows = null; 
			if(count($selected_tags_ids ) == 0){
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f join `fact_in_tag` as c on f.id=c.fact_id WHERE f.deleted!=1 ")  or die(mysql_error());
			}
			else{
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f join `fact_in_tag` as c on f.id=c.fact_id WHERE f.deleted!=1 AND c.tag_id IN (" . implode(',', $selected_tags_ids) . ") group by f.name")  or die(mysql_error());
			}		

			while($res = mysql_fetch_assoc($rows)) {
			    $facts[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $facts
				)
			);

		}
		else if( $task == 'get_all_intersection_facts' ){

			$selected_tags_ids = array(); 
			foreach ($data['selected_tags'] as $x ) {
				$selected_tags_ids[] = $x['id'];
			}
			$num_of_tags =count($selected_tags_ids) ; 
			$rows= null ; 
			
			if(count($selected_tags_ids ) == 0){
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f WHERE f.deleted!=1  ")  or die(mysql_error());
			}
			else{
				$rows = mysql_query("SELECT f.id, f.date, f.name, f.deleted FROM `facts` as f join `fact_in_tag` as c on f.id=c.fact_id WHERE f.deleted!=1 AND c.tag_id IN (" . implode(',', $selected_tags_ids) . ") group by f.name having count(*) = '$num_of_tags' ")  or die(mysql_error());
			}

			while($res = mysql_fetch_assoc($rows)) {
			    $facts[] = $res;
			}
			return new JsonModel (
				array(
					'list' => $facts
				)
			);

		}
	}

	public function getList(){

	}

}