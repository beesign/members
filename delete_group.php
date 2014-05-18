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
if(!isset($_GET['group_id']) OR !is_numeric($_GET['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_GET['group_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() <> 1) { die('No settings'); }

$settings_fetch = $query_settings->fetchRow();
if ($settings_fetch['delete_grp_members'] == 1) {
	// Delete all Aliases:
	$database->query("DELETE FROM ".TABLE_PREFIX."mod_members WHERE group_id = '$group_id' AND m_isalias > 0");
	
	//find all aliases of members:
	$query_members = $database->query("SELECT member_id FROM `".TABLE_PREFIX."mod_members` WHERE group_id = '$group_id'");
	if ($query_members->numRows() > 0) {
		while($member_fetch  = $query_members->fetchRow()) {
			$mnr = $member_fetch['member_id'];
			$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '".$mnr."'");
			if($query_alias->numRows() > 0) {
				while($alias = $query_alias->fetchRow()) {
					$database->query("DELETE FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$alias['member_id']."'");
					echo "alias deleted: ".$alias['member_id']."<br/>";
				}			
			}
		}	
		//Finaly: delete member
		$database->query("DELETE FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$mnr."'");
		echo "member deleted: ". $mnr."<br/>";
	}	
}



// Delete group
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members_groups WHERE group_id = '$group_id'");
include('tidy_up.inc.php');

$back_url =  ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if (isset($_GET['from']) AND $_GET['from'] == 2) { $back_url = WB_URL.'/modules/'.$mod_dir .'/modify_sort.php?page_id='.$page_id.'&section_id='.$section_id; }

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $back_url);
} else {
	$admin->print_success($TEXT['SUCCESS'], $back_url);
}

// Print admin footer
$admin->print_footer();

?>