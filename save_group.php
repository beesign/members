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

// Get id
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_POST['group_id'];
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
	

$active = $admin->get_post_escaped('active');
$group_name = $admin->get_post_escaped('group_name');
if ($group_name == '') {$group_name = 'untitled group';}

$group_desc = $admin->get_post_escaped('group_desc');
	
$group_name = htmlspecialchars($group_name);	
if ($html_allowed == 0) {$group_desc = htmlspecialchars($group_desc);}
	
//$group_name = addslashes($group_name);
//$group_desc = addslashes($group_desc);
	
$gobacktolist =  (int)$admin->get_post_escaped('gobacktolist');
$from = (int)$admin->get_post_escaped('from');

//clear cache:
	$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache='',  group_search='' WHERE group_id = '".$group_id."'");

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_name = '$group_name', group_desc = '$group_desc', active = '$active' WHERE group_id = '$group_id'");

// Check if there is a db error, otherwise say successful
$back_url =  ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if ($from == 2) { $back_url = WB_URL.'/modules/'.$mod_dir .'/modify_sort.php?page_id='.$page_id.'&section_id='.$section_id; }


$paramdelimiter = '&';
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id);
} else {	
	if ($gobacktolist == 1) {
		$admin->print_success($TEXT['SUCCESS'], $back_url);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id);
	}
}

// Print admin footer
$admin->print_footer();

?>