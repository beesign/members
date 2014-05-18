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
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }

$mod_dir = basename(dirname(__FILE__));
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

$html_allowed = 0;
require('kram/module_settings.default.php');
include('module_settings.php');
if (!defined('THEME_URL')) define ("THEME_URL", ADMIN_URL);

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

//if (isset($_GET['hlmember'])) {$hlmember = 0 + (int)$_GET['hlmember'];} else {$hlmember = 0;}

//Delete all links and groups with no m_name
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members  WHERE m_name=''");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members_groups  WHERE group_id > '1' AND group_name=''");

// Get information on what groups and members are sorted by
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() <> 1) { die('No settings'); }

$settings_fetch = $query_settings->fetchRow();
$sort_grp_name = $settings_fetch['sort_grp_name'];
if ($sort_grp_name == 1) {$sort_grp_by = "group_name";} else {$sort_grp_by = "position";}
	
$sort_mem_name = $settings_fetch['sort_mem_name'];
$sort_mem_desc = $settings_fetch['sort_mem_desc'];

// Sorting members by m_score - m_sortt - m_name or position
if ($sort_mem_desc == 1) {$sort_ad = ' DESC';} else {$sort_ad = ' ASC';}
$sort_by = "position".$sort_ad;
// Sorting members by m_score - m_sortt - m_name or position
if ($sort_mem_name == 1) {$sort_by = "m_name".$sort_ad;}
if ($sort_mem_name == 2) {$sort_by = "m_sortt".$sort_ad.", m_name".$sort_ad;}
if ($sort_mem_name == 3) {$sort_by = "m_score".$sort_ad.", m_sortt".$sort_ad.", m_name".$sort_ad;}

if ($sort_by != "position ASC") {die("not possible");}

$picurl = WB_URL.'/modules/'.$mod_dir.'/img/';
$mod_gl = WB_URL.'/modules/'.$mod_dir.'/modify_group.php?';
$mod_ml = WB_URL.'/modules/'.$mod_dir.'/modify_member.php?';
$add_ml = WB_URL.'/modules/'.$mod_dir.'/add_member.php?';
$mod_param = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'from=2'.$paramdelimiter.'group_id=';

$query_ghosts = $database->query("SELECT member_id FROM `".TABLE_PREFIX."mod_members` WHERE group_id='1'");
$countquery_ghosts = $query_ghosts->numRows();


// <!--Start Members modify.php  code-->
?>
<div id="mod_members_wrapper" class="mod_members">

<table width="30" border="0" cellspacing="0" cellpadding="0" class="mainmenue">
  <tr>
    <td><a href="<?php echo WB_URL .'/modules/'.$mod_dir.'/add_group.php?'. $mod_param.'"><img src="'.$picurl.'top_new.gif" title="'. $TEXT['ADD'].' '.$METEXT['GROUP'].'" alt="'. $TEXT['ADD'].' '.$METEXT['GROUP']; ?>" width="80" height="47" border="0" /></a></td>
    <?php if ($noadmin_nooptions > 0 AND $admin->get_group_id() != 1 ) {} else { echo '<td><a href="'.WB_URL .'/modules/'.$mod_dir.'/modify_settings.php?'. $mod_param.'"><img src="'.$picurl.'top_options.gif" title="'. $TEXT['SETTINGS'].'" alt="'. $TEXT['SETTINGS']. '" width="41" height="47" border="0" /></a></td>' ;}?>
    <?php if ($countquery_ghosts > 0) {echo '<td><a href="'.WB_URL .'/modules/'.$mod_dir.'/find_ghosts.php?'. $mod_param.'1"><img src="'.$picurl.'top_ghosts.gif" title="'. $METEXT['MANAGEGHOSTS'].'" alt="'. $METEXT['MANAGEGHOSTS'].'" width="41" height="47" border="0" /></a></td>'; } ?>
  	<td><a href="<?php echo ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'"><img src="'.$picurl.'top_sort1.gif" title="'. $METEXT['SORT'].'" alt="'. $METEXT['SORT']; ?>" width="41" height="47" border="0" /></a></td>
	<td><a href="<?php echo WB_URL .'/modules/'.$mod_dir.'/help.php?'. $mod_param.'"><img src="'.$picurl.'top_help.gif" title="'. $MENU['HELP'].'" alt="'. $MENU['HELP']; ?>" width="41" height="47" border="0" /></a></td>
  
  </tr>
</table>
<p>&nbsp;</p>	

<?php 
$the_group = 0;
// Loop through existing groups
$query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members_groups` WHERE section_id = '$section_id' ORDER BY ".$sort_grp_by." ASC");
$countquery_groups = $query_groups->numRows();
if($countquery_groups > 0) {
	$countgroups = 0;
	while($group_fetch = $query_groups->fetchRow()) {
		$group_id = $group_fetch['group_id'];		
		$group_active = $group_fetch['active'];
		$countgroups++;
		if ($the_group == 0) {
			$the_group = $group_id;
			echo '<script type="text/javascript">
			var the_group = '.$the_group.';
			var WB_URL = "'.WB_URL.'";
			</script>
			';
			
			echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" class="sortgroup">
			<tr>
			<td width="50" class="grouptd1">
				<a href="'.$mod_gl.$mod_param.$group_id.'"><img src="'.$picurl.'modg.gif" alt="Modify Group" title="Modify Group"/></a>
				<a href="'.$add_ml.$mod_param.$group_id.'"><img src="'.$picurl.'addgm.gif" alt="Add Member" title="Add Member"/></a>
			</td>
			<td class="grouptd2"><a href="'.$mod_gl.$mod_param.$group_id.'">'.stripslashes($group_fetch['group_name']).'</a></td>			
			<td width="30"><img src="'.$picurl.'gactive'. $group_active.'.gif" alt="" /></td>
			<td width="30">';
				if ($countgroups > 1 AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_up.php?'.$mod_param.$group_id.'" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" border="0" alt="^" /></a>';} else {echo "&nbsp;";}
			echo '</td>
			<td width="30">';
				if ($countgroups < $countquery_groups AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_down.php?'.$mod_param.$group_id.$paramdelimiter.'from=2" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" border="0" alt="v" /></a>';} else {echo "&nbsp;";}
			echo '</td>
			<td width="30">
				<a href="#" onclick="javascript: confirm_link(\''.$TEXT['ARE_YOU_SURE']."', '".WB_URL.'/modules/'.$mod_dir.'/delete_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id.'\');" title="'.$TEXT['DELETE'].'">
					<img src="'.THEME_URL.'/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		</table>
			<div id="dragableTable"><ul>';
		} else {
			$g_id = 1000000 + $group_id;
			echo '<li id="recordsArray_'.$g_id.'"><table cellpadding="2" cellspacing="0" border="0" width="100%" class="sortgroup">
			<tr>
			<td width="50" class="grouptd1">
				<a href="'.$mod_gl.$mod_param.$group_id.'"><img src="'.$picurl.'modg.gif" alt="Modify Group" title="Modify Group"/></a>
				<a href="'.$add_ml.$mod_param.$group_id.'"><img src="'.$picurl.'addgm.gif" alt="Add Member" title="Add Member"/></a>
			</td>
			<td class="grouptd2"><a href="'.$mod_gl.$mod_param.$group_id.'">'.stripslashes($group_fetch['group_name']).'</a></td>			
			<td width="30"><img src="'.$picurl.'gactive'. $group_active.'.gif" alt="" /></td>
			<td width="30">';
				if ($countgroups > 1 AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_up.php?'.$mod_param.$group_id.$paramdelimiter.'from=2" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" border="0" alt="^" /></a>';} else {echo "&nbsp;";}
			echo '</td>
			<td width="30">';
				if ($countgroups < $countquery_groups AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_down.php?'.$mod_param.$group_id.$paramdelimiter.'from=2" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" border="0" alt="v" /></a>';} else {echo "&nbsp;";}
			echo '</td>
			<td width="30">
				<a href="#" onclick="javascript: confirm_link(\''.$TEXT['ARE_YOU_SURE']."', '".WB_URL.'/modules/'.$mod_dir.'/delete_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id.'\');" title="'.$TEXT['DELETE'].'">
					<img src="'.THEME_URL.'/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		</table></li>';
		}	
/*
		// DIVs instead members table	
		echo '&nbsp;<div class="mod_members_group">'."\n"; 
			echo '<div style="width: 50px; height: 30px; padding: 3px; float:left; text-align:left; vertical-align:middle;">'."\n";
			echo '<a href="'.$mod_gl.$mod_param.$group_id.'"><img src="'.$picurl.'modg.gif" alt="Modify Group" title="Modify Group"/></a>'."\n";
			echo '<a href="'.$add_ml.$mod_param.$group_id.'"><img src="'.$picurl.'addgm.gif" alt="Add Member" title="Add Member"/></a>'."\n";
			echo '</div>'."\n";
			echo '<div style="width: 700px; height: 30px; padding-left: 15px; padding-top: 7px; float:left; text-align:left; vertical-align:middle;">'."\n";
			echo '<a href="'.$mod_gl.$mod_param.$group_id.'">'.stripslashes($group_fetch['group_name']).'</a>'."\n";		
			echo '</div>'."\n";		
			echo '<div style="width: 25px; height: 30px; padding: 3px; float:left; text-align:left; vertical-align:middle;">'."\n";				
			echo '<img src="'.$picurl.'gactive'. $group_active.'.gif" alt="" />'."\n";
			echo '</div>'."\n";	
			echo '<div style="width: 25px; height: 30px; padding: 3px; float:left; text-align:left; vertical-align:middle;">'."\n";				
			if ($countgroups > 1 AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_up.php?'.$mod_param.$group_id.'" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" border="0" alt="^" /></a>';} else {echo "&nbsp;";};
			echo '</div>'."\n";
			echo '<div style="width: 25px; height: 30px; padding: 3px; float:left; text-align:left; vertical-align:middle;">'."\n";	
			if ($countgroups < $countquery_groups AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_down.php?'.$mod_param.$group_id.'" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" border="0" alt="v" /></a>';} else {echo "&nbsp;";};
			echo '</div>'."\n";	
			echo '<div style="width: 25px; height: 30px; padding: 3px; float:right; vertical-align:middle;">'."\n";				
			echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/delete_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id.'" title="'.$TEXT['DELETE'].'">'."\n";
			echo '<img src="'.THEME_URL.'/images/delete_16.png" border="0" alt="X" /></a>'."\n";
			echo '</div>'."\n";
			echo '<div style="clear:both; display:block; visibility:hidden;">'."\n";
			echo '</div>'."\n";
		echo '</div>'."\n";
*/		
		
		//echo '<div id="dragableTable_'.$group_id.'">'."\n";
		//echo '<ul>'."\n";
		
		
		// Loop through existing members

		$query_members = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE group_id = '$group_id' ORDER BY ".$sort_by);

		$countquery_members = $query_members->numRows();
		if($countquery_members > 0) {		
			$countmembers = 0;
			$countalias = 0;
			$countaliasofmembers = 0;
			include('kram/memberlist.inc-t.php');
		//echo '<hr/>';
		// end loop through members
		} 
		
		// CLOSE ul i div i table
		?>
		<!--/ul-->
		<!--/div-->
		<!--div style="clear:both; height: 10px; display:block; visibility:hidden;"></div>
				
		// Ovdje tabelu koja je bila prije
		<div style="width: 100%; padding:5px 0 20px 10px;"><br /><br /-->
		
		<?php //echo '<a href="'.$add_ml.$mod_param.$group_id.'">'.$TEXT['ADD'].' '.$METEXT['MEMBER'].'</a></div>'."\n";
				
	}
	echo '</ul></div>'."\n";
} else {
	echo '<a href="'.WB_URL .'/modules/'.$mod_dir.'/add_group.php?'. $mod_param.'" style="padding-top:20px;"><strong>'.$TEXT['ADD'].' '.$METEXT['GROUP'].'</strong></a>';	

}

// Now show EDIT CSS button
//css_edit();
echo '<hr/><br />';

// write JS to Backend Body JS file - now we close file write connection
//fclose($fh);
	
// div za printanje statusa reordera
echo '<div id="dragableResult">	<p>Reorder result will be displayed here.&nbsp; </p>		</div>';

// end mod_membres_wrapper
echo '</div>';
$admin->print_footer();
 ?>

<!--Stop Members modify.php  code-->
