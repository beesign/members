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


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

require('kram/module_settings.default.php');
include('module_settings.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}


// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

$linkback = WB_URL.'/modules/'.$mod_dir.'/find_ghosts.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id=1';

$to_do = $_POST['to_do'];
$moveto = 0;
if (substr($to_do, 0, 7) == 'moveto_') { $moveto = (int)str_replace('moveto_', '', $to_do); }
$hlmember=0;
foreach ($_POST as $value) {
	//loop through selected members:
	if (substr($value, 0, 5) <> 'memb_') { continue; } //next
			
	$mnr = (int)str_replace('memb_', '', $value);
	if ($hlmember==0) $hlmember = $mnr;
	//if delete: 
	if ($moveto == 0) {
	//delete also all aliases:
		$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '".$mnr."'");
		if($query_alias->numRows() > 0) {
			while($alias = $query_alias->fetchRow()) {
				$database->query("DELETE FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$alias['member_id']."'");
				echo "alias deleted: ".$alias['member_id'].': '.$alias['m_name']."<br/>";				
			}			
		}
		//Finaly: delete member
		$database->query("DELETE FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$mnr."'");
		echo "member deleted: ". $mnr."<br/>";
		
	} else {
		
		//Move to group $moveto :
		
		//change page_id to get back to new page:
		$query_groups = $database->query("SELECT page_id, section_id FROM `".TABLE_PREFIX."mod_members_groups` WHERE group_id = '".$moveto."'");
		if($query_groups->numRows() > 0) {
			$group_fetch = $query_groups->fetchRow();
			$page_id = $group_fetch['page_id'];
			$section_id = $group_fetch['section_id'];
		}
		
		//change position:
		$order = new order(TABLE_PREFIX.'mod_members', 'position', 'member_id', 'group_id');
		$position = $order->get_new($moveto);
		$database->query("UPDATE ".TABLE_PREFIX."mod_members SET position='".$position."'WHERE member_id='".$mnr."'");
		echo "new position: ". $mnr."<br/>";
		
		//move to group:
		$database->query("UPDATE ".TABLE_PREFIX."mod_members SET group_id='".$moveto."'WHERE member_id='".$mnr."'");
		echo "member moved: ". $mnr."<br/>";
							
		
		$linkback = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.$paramdelimiter.'hlmember='.$hlmember;
		
	}	
} ?>
<p>&nbsp;</p>

<form name="back" action="<?php echo $linkback ?>" method="post" style="margin: 0;">
	<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
    <input type="submit" name="Submit" value="Back">	
</form>
	
<?php



// Print admin footer
$admin->print_footer();

?>