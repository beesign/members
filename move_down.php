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
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));

// Get id
if(!isset($_GET['member_id']) OR !is_numeric($_GET['member_id'])) {
	if(!isset($_GET['group_id']) OR !is_numeric($_GET['group_id'])) {
		header("Location: index.php");
	} else {
		$id = $_GET['group_id'];
		$id_field = 'group_id';
		$table = TABLE_PREFIX.'mod_members_groups';
	}
} else {
	$id = $_GET['member_id'];
	$id_field = 'member_id';
	$table = TABLE_PREFIX.'mod_members';
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

require('kram/module_settings.default.php');
include('module_settings.php');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

$back_url =  ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if (isset($_GET['from']) AND $_GET['from'] == 2) { $back_url = WB_URL.'/modules/'.$mod_dir .'/modify_sort.php?page_id='.$page_id.'&section_id='.$section_id; }


// Create new order object an reorder
if ($id_field == 'group_id') {
	$order = new order($table, 'position', $id_field, 'section_id');
} else {
	$order = new order($table, 'position', $id_field, 'group_id');
	$back_url .= $paramdelimiter.'hlmember='.$id;
	//clear cache in oldgroup
	$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache='',  group_search='' WHERE group_id = '".$_GET['group_id']."'");
}


if($order->move_down($id)) {
	$admin->print_success($TEXT['SUCCESS'], $back_url);
} else {
	$admin->print_error($TEXT['ERROR'], $back_url);
}

// Print admin footer
$admin->print_footer();

?>