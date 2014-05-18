<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');
// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_POST['member_id']) OR !is_numeric($_POST['member_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$member_id = $_POST['member_id'];
}

$mod_dir = basename(dirname(__FILE__));
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

$html_allowed = 0;
require('kram/module_settings.default.php');
include('module_settings.php');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

$m_name = $admin->get_post_escaped('m_name');
if ($m_name == '') {$m_name = 'untitled';}

$m_sortt = addslashes(strip_tags($admin->get_post_escaped('m_sortt')));
$m_score = (int)$admin->get_post_escaped('m_score');
$from = (int)$admin->get_post_escaped('from');
	
	
$isalias = (int) $admin->get_post_escaped('isalias');
if ($isalias == 0) {  //is NO alias, search for aliases od this member
	$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '$member_id'");
	if($query_alias->numRows() > 0) {
		//Fix m_sortt, m_name:
	 $database->query("UPDATE ".TABLE_PREFIX."mod_members SET m_name='".addslashes($m_name)."',  m_sortt='".$m_sortt."' WHERE m_isalias = '".$member_id."'");
	}
}


//Default:
if ($html_allowed > 3) {$html_allowed = 3;}
$longusage1 = 0; $longusage2 = 0;
if ($html_allowed > 0) {$longusage1 = 1; $longusage2 = 1;}
if ($html_allowed > 1) {$longusage1 = 2; $longusage2 = 0;}
if ($html_allowed > 2) {$longusage1 = 2; $longusage2 = 2;}

//GetSettings

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() != 1) { exit('Sh...t - no settings!'); }
$settings_fetch = $query_settings->fetchRow();	
if(!isset($settings_fetch['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_members_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "various_values" added</h2>';
} else {
	if ($settings_fetch['various_values'] != '') {
		$vv = explode(',',$settings_fetch['various_values']);
		$longusage1 = (int) $vv[0];
		$longusage2 = (int) $vv[1];					
	}
}

	
$group_id = (int)$admin->get_post_escaped('group_id');
$newgroup = (int)$admin->get_post_escaped('newgroup');
$active = (int)$admin->get_post_escaped('active');
$m_memberpage_id = (int)$admin->get_post_escaped('m_memberpage_id');
	
$m_picture = strip_tags($admin->get_post_escaped('m_picture'));
$m_link = trim(strip_tags($admin->get_post_escaped('m_link')));	
	
	
	
$m_short1 = $admin->get_post_escaped('m_short1');		
$m_short2 = $admin->get_post_escaped('m_short2');	
$m_long1 = $admin->get_post_escaped('m_long1');
$m_long2 = $admin->get_post_escaped('m_long2');

	
//Only HTMl when $html_allowed == 1 
if ($html_allowed != 1) {
	$m_name = htmlspecialchars($m_name);
	$m_short1 = htmlspecialchars($m_short1);			
	$m_short2 = htmlspecialchars($m_short2);			
}

	
//HTMl when $html_allowed >= 1
//because: 1 = use HTML, >1 = use Wysiwyg
if ($longusage1 == 0) {  $m_long1 = htmlspecialchars($m_long1); }
if ($longusage2 == 0) {  $m_long2 = htmlspecialchars($m_long2); }


$m_name  = addslashes($m_name);
$m_short1 = addslashes($m_short1);			
$m_short2 = addslashes($m_short2);
$m_long1 = addslashes($m_long1);			
$m_long2 = addslashes($m_long2);
	


	
$gobacktolist =  (int) $_POST['gobacktolist'];
$doduplicate =  (int) $_POST['duplicate'];
if ($doduplicate == 1) {$m_name .= " DUPLICATE"; }



//clear cache
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache='',  group_search=''");
		
if ($newgroup <> $group_id AND $newgroup > 1) {
		echo "moved from: ".$group_id. "  to: " .$newgroup;
			
		
		$group_id = $newgroup;
		
		//change page_id to get back to new page!
		$query_groups = $database->query("SELECT page_id, section_id FROM `".TABLE_PREFIX."mod_members_groups` WHERE group_id = '".$group_id."'");
		if($query_groups->numRows() > 0) {
			$group_fetch = $query_groups->fetchRow();
			$page_id = $group_fetch['page_id'];
			$section_id = $group_fetch['section_id'];
		}
		
		$order = new order(TABLE_PREFIX.'mod_members', 'position', 'member_id', 'group_id');
		$position = $order->get_new($group_id);
}

if ($newgroup == 1) { $group_id=1;}


$querybase = 		(" group_id = '$group_id', "
					. " m_name = '$m_name', "
					. " m_isalias = '$isalias', "
					. " active = '$active', "					
					. " m_sortt = '$m_sortt', "
					. " m_score = '$m_score', "
					. " m_short1 = '$m_short1', "
					. " m_short2 = '$m_short2', "
					. " m_long1 = '$m_long1', "
					. " m_long2 = '$m_long2', "
					. " m_memberpage_id = '$m_memberpage_id', "
					. " m_link = '$m_link', "
					. " m_picture = '$m_picture'");

if ($doduplicate == 1) {
	//duplicate:
	$order = new order(TABLE_PREFIX.'mod_members', 'position', 'member_id', 'group_id');
	$position = $order->get_new($group_id);
	$query = "INSERT INTO ".TABLE_PREFIX."mod_members SET " . $querybase. ", position='$position'";
	$database->query($query);
	// Get the id
	$member_id = $database->get_one("SELECT LAST_INSERT_ID()");

} else {
	// Update row
	$query = "UPDATE ".TABLE_PREFIX."mod_members SET " . $querybase;
	if (isset($position)) {$query .= ", position = '$position' ";}					
	$query .= " WHERE member_id = '$member_id'";
	$database->query($query);
}

$paramdelimiter = '&';					
$back_url =  ADMIN_URL.'/pages/modify.php?page_id='.$page_id.$paramdelimiter.'hlmember='.$member_id;
if ($from == 2) { $back_url = WB_URL.'/modules/'.$mod_dir.'/modify_sort.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'hlmember='.$member_id; }

// Check if there is a db error, otherwise say successful

if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_member.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'member_id='.$member_id);
} else {	
	if ($gobacktolist == 1) {
		$admin->print_success($TEXT['SUCCESS'], $back_url);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_member.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'member_id='.$member_id);
	}
}




// Print admin footer
$admin->print_footer();

?>