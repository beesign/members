<?php
// First we prevent direct access and check for variables
if(!isset($_POST['action']) OR !isset($_POST['recordsArray']) OR !isset($_POST['group']) OR (!is_numeric($_POST['group']))) {
	// now we redirect to index, if you are in subfolder use ../index.php
	header( 'Location: ../../index.php' ) ;
} else {

	// Now we set the path to config file
	require('../../../config.php');

	// check if user has permissions to access the Members module
	require_once(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('Modules', 'module_view', false, false);
	if (!($admin->is_authenticated() && $admin->get_permission('members', 'module'))) 
		die(header('Location: ../../index.php'));
	
// DO I NEED THEESE TWO LINES BELOW ?????
	//global $database;
	//global $wb;
   
	//	Unsanitized variables 
	//	$action = $_POST['action'];
	//	$updateRecordsArray     = $_POST['recordsArray'];
	
	// Sanitized variables
	$action = $admin->add_slashes($_POST['action']);
	$updateRecordsArray = isset($_POST['recordsArray']) ? $_POST['recordsArray'] : array();

	// Also get group_id so we can reorder multiple groups	
	$the_group = (int)($_POST['group']); 
	if ($the_group == 0) {exit("no group");}
	 
// This line verifies that in &action is not other text than "updateRecordsListings", if something else is inputed (to try to HACK the DB), there will be no DB access..
	if ($action == "updateRecordsListings"){
	 
		$listingCounter = 1;
		$output = "";
		foreach ($updateRecordsArray as $recordIDValue) {
			if ($recordIDValue > 1000000) {
				$the_group = $recordIDValue - 1000000;
				$listingCounter = 1;
				$output .= "-------------\n";
				continue;
	 		}
			//$database->query("UPDATE `".TABLE_PREFIX."mod_members` SET `position` = ".$listingCounter." WHERE `member_id` = ".$recordIDValue." AND `group_id` = ".$group_id." ");
			$database->query("UPDATE `".TABLE_PREFIX."mod_members` SET position = ".$listingCounter.", group_id = ".$the_group." WHERE `member_id` = ".$recordIDValue);
			$output .= "m".$recordIDValue. ' p'.$listingCounter. ' g'.$the_group."\n";


			$listingCounter ++;
		}
	 
		// now we can print the result in green field
		echo 'Reorder result: ';
		echo '<pre>';
		echo $output;
		//print_r($updateRecordsArray);
		echo '</pre>';
		echo 'You successfuly reordered';

	}
} // this ends else statement from the top of the page
?>