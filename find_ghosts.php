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
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');
// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

require('kram/module_settings.default.php');
include('module_settings.php');
include('kram/tidy_up.inc.php');

$picurl = WB_URL.'/modules/'.$mod_dir.'/img/';
$mod_gl = WB_URL.'/modules/'.$mod_dir.'/modify_group.php?';
$mod_ml = WB_URL.'/modules/'.$mod_dir.'/modify_member.php?';
$add_ml = WB_URL.'/modules/'.$mod_dir.'/add_member.php?';
$mod_param = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'group_id=';
$is_ghost_page = 1;
$group_id = 1;
$hlmember = 0;



echo '<form name="ghosts" method="post" action="'.WB_URL.'/modules/'.$mod_dir.'/change_ghosts.php">'; ?>
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">

<table cellpadding="2" cellspacing="0" border="0" width="100%" class="membertable">
<tr class="grouptr">
			<td width="80" class="grouptd1">&nbsp;</td>
			<td class="grouptd2"><?php echo $METEXT['GHOSTS']; ?></td>			
			<td width="30">&nbsp;</td>			
		</tr>


<?php 
// Loop through existing members
$sort_mem_name = 1;
$group_id = 1;
$group_active = 1;
$countmembers = 0;
$countalias = 0;
$countaliasofmembers = 0;
			
$query_members = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE group_id = '1' ORDER BY m_sortt,m_name ASC");
if($query_members->numRows() > 0) {	
	while($members = $query_members->fetchRow()) {
	$isalias = 0 + (int)$members['m_isalias'];
	$member_id = 0 + (int)$members['member_id'];
	$countmembers++; 
	if ($isalias > 0) $countalias++;
	
		?>
		<tr class="mrow<?php if($isalias > 0) echo " alias"; ?>" onmouseover="this.style.backgroundColor = '#F1F8DD'" onmouseout="this.style.backgroundColor = '#ffffff'">
			<td class="membertd1" width="80">
			<?php echo '<input type="checkbox" name="m_'.$member_id.'" value="memb_'.$member_id.'">'; ?>
				<a href="<?php echo $mod_ml.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id.'"><img src="'.$picurl.'mod'; if ($isalias == 0) {echo 'm';} else {echo 'a';}?>.gif" alt="Modify" /></a>
			</td>
			<td class="membertd2">
				<a href="<?php echo WB_URL.'/modules/'.$mod_dir.'/modify_member.php?'.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id; ?>">
					<?php echo stripslashes($members['m_name']); 
					if ($isalias == 0) {  //is NO alias, search for aliases od this member
						$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '$member_id'");
						$has_alias = $query_alias->numRows();
						if($has_alias > 0) {
							echo ' <span class="has_alias">('.$has_alias.'*)</span>';
							$countaliasofmembers += $has_alias;
						}
					} ?>
				</a>
			</td>
			
			<td width="30"><img src="<?php echo $picurl.'mactive'.$group_active.$members['active'].'.gif" alt="'.$TEXT['ACTIVE'].': '; if($members['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>" /></td>			
		</tr>
		
		
		<?php		
	}
	
	echo '<tr ><td colspan="3" class="groupinfo">'.$countmembers.' '.$METEXT['ITEMS']; if ($countalias > 0) {echo ', '.$METEXT['PARTALIASES'].': '. $countalias; } if ($countaliasofmembers > 0) {echo ', (*)'. $METEXT['MEMWITHALIASES'].': '.$countaliasofmembers; } echo '</td></tr>' ;
	//if ($countaliasofmembers > 0) {echo '<tr><td colspan="3" class="warning">'.$METEXT['MEMBERS'].'hdgfhdgfhdfhfghdgfhfghgdfhdgfhfdghdfgh</td></tr>';}		
} else {
	echo '<tr><td colspan="3">'.$TEXT['NONE_FOUND'].'</td></tr>';
} ?>
</table>
<?php if ($countaliasofmembers > 0) {echo '<div class="warning">'.$METEXT['INFODELETEMEMBER'].'</div>';} ?>
	

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td align="left" nowrap>
	<?php //the Group Selection box:
if ($countmembers > 0) {
	$m_selection = '<select style="width:150px;" name="to_do"><option value="delete">'. $TEXT['DELETE']."</option>\n";
	$m_selection .= '<option value="#">--'. $METEXT['MOVETO']."--</option>\n";
	
	$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups ORDER BY page_id,position ASC");				
	if($query->numRows() > 0) {			
		// Loop through groups
		while($group = $query->fetchRow()) {
			$gid = $group['group_id'];									
			if ( $gid  < 2) {continue;}
			$m_selection .=  '<option value="moveto_'.$gid.'">'. stripslashes($group['group_name'])."</option>\n";	
		}
		$m_selection .= '</select>'."\n"; 
		echo $m_selection ; 
		} else {
		echo "no groups found" ; 
		}?>
		 <input type="submit" name="do_this" value="<?php echo $METEXT['APPLY']; ?>" style="width: 200px; margin-top: 5px;">					
<?php } else {echo '&nbsp;';} ?>
</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</form>
<?php 
// Print admin footer
$admin->print_footer();

?>