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

$mod_dir = basename(dirname(__FILE__));
require('kram/module_settings.default.php');
include('module_settings.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');	

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Get new order
$group_id=(int)$_GET['group_id'];
if ($group_id==0) { exit("no group"); }

$isalias = 0;
if (isset($_GET['addalias'])) {$isalias=(int)$_GET['addalias'];}

$order = new order(TABLE_PREFIX.'mod_members', 'position', 'member_id', 'group_id');
$position = $order->get_new($group_id);


// Insert new row into database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_members (group_id,position,active,m_isalias) VALUES ('$group_id','$position','1','$isalias')");

// Get the id
$member_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
$theurl = WB_URL.'/modules/'.$mod_dir.'/modify_member.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id.$paramdelimiter.'member_id='.$member_id;
if($database->is_error()) {
	$admin->print_error($database->get_error(), $theurl);
} else {
	$admin->print_success($TEXT['SUCCESS'], $theurl);
}

// Print admin footer
$admin->print_footer();

?>