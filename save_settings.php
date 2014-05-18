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
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');


// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');


$header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['header']));
$footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['footer']));

$grp_head   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['grp_head']));
$grp_foot = $admin->add_slashes(str_replace($friendly, $raw, $_POST['grp_foot']));
$member_loop   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['member_loop']));

$t_memberpage_id = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_memberpage_id']));
$t_link   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_link']));
$t_short1   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_short1']));
$t_short2   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_short2']));
$t_long1   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_long1']));
$t_long2   = $admin->add_slashes(str_replace($friendly, $raw, $_POST['t_long2']));

$longusage1 = (int) $_POST['longusage1'];
$longusage2 = (int) $_POST['longusage2'];
$various_values = ''.$longusage1.','.$longusage2;



$pic_loc = $admin->add_slashes(str_replace($friendly, $raw, $_POST['pic_loc']));
$extensions = $_POST['extensions'];
$satzz = array( '.', ',', '|', '/', '*', ';', '\"', '\'', '  ');
$extensions = str_replace($satzz, '', $extensions);
$extensions = str_replace($satzz, '', $extensions);
$extensions = addslashes(trim($extensions));




$delete_grp_members = (int) $_POST['delete_grp_members'];
$sort_grp_name = (int) $_POST['sort_grp_name'];
$sort_mem_name = (int) $_POST['sort_mem_name'];
$sort_mem_desc = $_POST['sort_mem_desc'];
$hide_email = (int) $_POST['hide_email'];
$gobacktolist =  (int) (int)$_POST['gobacktolist'];

if (isset($forall)) {$forall = (int)$_POST['forall'];} else {$forall = 0;}

//Clear Cache:
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache= ''");
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_search= ''");

// Update settings
$thequery = ("UPDATE ".TABLE_PREFIX."mod_members_settings SET "
					." t_memberpage_id = '$t_memberpage_id', "
					." t_link = '$t_link', "
					." t_short1 = '$t_short1', "
					." t_short2 = '$t_short2', "
					." t_long1 = '$t_long1', "
					." t_long2 = '$t_long2', "
					." various_values = '$various_values', "															
					." header = '$header', "
					." footer = '$footer', "
					." grp_head = '$grp_head', "
					." grp_foot = '$grp_foot', "
					." member_loop = '$member_loop', "					
					." hide_email = '$hide_email', "
					." pic_loc = '$pic_loc', "
					." extensions = '$extensions', "
					." sort_grp_name = '$sort_grp_name', "
					." sort_mem_name = '$sort_mem_name', "
					." sort_mem_desc = '$sort_mem_desc', "
					." delete_grp_members = '$delete_grp_members'");
					
					if ($forall <> 1) {$thequery .= " WHERE section_id = '$section_id'";}
					
//die($thequery);					
$database->query(str_replace('?php', '', $thequery));

// Check if there is a db error, otherwise say successful.
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {	
	if ($gobacktolist == 1) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	}
}

// Print admin footer
$admin->print_footer();

?>