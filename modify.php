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

// Must include code to stop this file being access directly
//require('../../config.php');
// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));

// include functions to edit the optional module CSS files (frontend.css, backend.css)
require_once('css.functions.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/members/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/members/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/members/languages/'.LANGUAGE.'.php');
	}
}

if (!defined('THEME_URL')) define ("THEME_URL", ADMIN_URL);

require('kram/module_settings.default.php');
include('module_settings.php');
if (isset($_GET['hlmember'])) {$hlmember = (int)$_GET['hlmember'];} else {$hlmember = 0;}

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

$allowdragdropsort = 0;
if ($sort_by == "position ASC") {$allowdragdropsort = 1;}


$picurl = WB_URL.'/modules/'.$mod_dir.'/img/';
$mod_gl = WB_URL.'/modules/'.$mod_dir.'/modify_group.php?';
$mod_ml = WB_URL.'/modules/'.$mod_dir.'/modify_member.php?';
$add_ml = WB_URL.'/modules/'.$mod_dir.'/add_member.php?';
$mod_param = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id=';

$query_ghosts = $database->query("SELECT member_id FROM `".TABLE_PREFIX."mod_members` WHERE group_id='1'");
$countquery_ghosts = $query_ghosts->numRows();

?>
<!--Start Members modify.php  code-->
<div class="mod_members">
<table width="30" border="0" cellspacing="0" cellpadding="0" class="mainmenue">
  <tr>
    <td><a href="<?php echo WB_URL .'/modules/'.$mod_dir.'/add_group.php?'. $mod_param.'"><img src="'.$picurl.'top_new.gif" title="'. $TEXT['ADD'].' '.$METEXT['GROUP'].'" alt="'. $TEXT['ADD'].' '.$METEXT['GROUP']; ?>" width="80" height="47" border="0" /></a></td>
    <?php if ($noadmin_nooptions > 0 AND $admin->get_group_id() != 1 ) {} else { echo '<td><a href="'.WB_URL .'/modules/'.$mod_dir.'/modify_settings.php?'. $mod_param.'"><img src="'.$picurl.'top_options.gif" title="'. $TEXT['SETTINGS'].'" alt="'. $TEXT['SETTINGS']. '" width="41" height="47" border="0" /></a></td>' ;}?>
    <?php if ($countquery_ghosts > 0) {echo '<td><a href="'.WB_URL .'/modules/'.$mod_dir.'/find_ghosts.php?'. $mod_param.'1"><img src="'.$picurl.'top_ghosts.gif" title="'. $METEXT['MANAGEGHOSTS'].'" alt="'. $METEXT['MANAGEGHOSTS'].'" width="41" height="47" border="0" /></a></td>'; } ?>
    <?php if ($allowdragdropsort == 1) { echo '<td><a href="'.WB_URL .'/modules/'.$mod_dir.'/modify_sort.php?'. $mod_param.'"><img src="'.$picurl.'top_sort2.gif" title="'. $METEXT['SORT'].'" alt="'. $METEXT['SORT'].'" width="41" height="47" border="0" /></a></td>'; } ?>
 	<td><a href="<?php echo WB_URL .'/modules/'.$mod_dir.'/help.php?'. $mod_param.'"><img src="'.$picurl.'top_help.gif" title="'. $MENU['HELP'].'" alt="'. $MENU['HELP']; ?>" width="41" height="47" border="0" /></a></td>
	
  </tr>
</table>
<br />



<table cellpadding="2" cellspacing="0" border="0" width="100%" class="membertable">
<?php



echo mysql_error();
// Loop through existing groups
$query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members_groups` WHERE section_id = '$section_id' ORDER BY ".$sort_grp_by." ASC");
$countquery_groups = $query_groups->numRows();
if($countquery_groups > 0) {
$countgroups = 0;
	while($group_fetch = $query_groups->fetchRow()) {
		$group_id = $group_fetch['group_id'];		
		$group_active = $group_fetch['active'];
		$countgroups++;
		
		?>
		<tr class="grouptr">
			<td width="50" class="grouptd1">
				<a href="<?php echo $mod_gl.$mod_param.$group_id.'"><img src="'.$picurl; ?>modg.gif" alt="Modify Group" title="Modify Group"/></a>
				<a href="<?php echo $add_ml.$mod_param.$group_id.'"><img src="'.$picurl; ?>addgm.gif" alt="Add Member" title="Add Member"/></a>
			</td>
			<td class="grouptd2"><a href="<?php echo $mod_gl.$mod_param.$group_id.'">'.$group_fetch['group_name']; ?></a></td>			
			<td width="30"><img src="<?php echo $picurl.'gactive'. $group_active; ?>.gif" alt="" /></td>
			<td width="30">
				<?php if ($countgroups > 1 AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_up.php?'.$mod_param.$group_id.'" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" border="0" alt="^" /></a>';} else {echo "&nbsp;";}?>
			</td>
			<td width="30">
				<?php if ($countgroups < $countquery_groups AND $sort_grp_name != 1) {echo '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move_down.php?'.$mod_param.$group_id.'" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" border="0" alt="v" /></a>';} else {echo "&nbsp;";}?>
			</td>
			<td width="30">
				<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL.'/modules/'.$mod_dir.'/delete_group.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id='.$group_id; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		
		
		<?php //-------------
		
		
		
		
		// Loop through existing members

	$query_members = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE group_id = '$group_id' ORDER BY ".$sort_by);

	$countquery_members = $query_members->numRows();
	if($countquery_members > 0) {		
		$countmembers = 0;
		$countalias = 0;
		$countaliasofmembers = 0;
		include('kram/memberlist.inc.php');
		//echo '<tr ><td colspan="6" class="groupinfo">'.$countmembers.'/'.$countquery_members.' '.$METEXT['ITEMS']; if ($countalias > 0) {echo ', '.$METEXT['PARTALIASES'].': '. $countalias; } if ($countaliasofmembers > 0) {echo ', (*)'. $METEXT['MEMWITHALIASES'].': '.$countaliasofmembers; } echo '</td></tr>' ;
	
	} 
	echo '<tr><td colspan="6" style="padding:5px 0 20px 57px;"><a href="'.$add_ml.$mod_param.$group_id.'">'.$TEXT['ADD'].' '.$METEXT['MEMBER'].'</a></td></tr>';
	//----------------------------------
		
		
		
	
}
?>
	
</table>
	<?php
	
} else {
	echo '<a href="'.WB_URL .'/modules/'.$mod_dir.'/add_group.php?'. $mod_param.'" style="padding-top:20px;"><strong>'.$TEXT['ADD'].' '.$METEXT['GROUP'].'</strong></a>';	
}

//echo '<div class="admininfo ">sort_by: '.$sort_by.'</div>';
css_edit(); echo '<hr/>';
?>
</div> 

<!--Stop Members modify.php  code-->


