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


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

echo "\n\n<!--Start Members modify_settings.php  code-->\n\n";
$mod_dir = basename(dirname(__FILE__));
// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
    }
}

$thefieldcontentheight = 15;
require('kram/module_settings.default.php');
include('module_settings.php');

if ($use_getfrom) { echo '<a href="#" onclick="makevisible(\'getfromtable\');" >Get from</a>'; }
if ($use_getfrom && $use_presets) echo " | ";
if ($use_presets) { echo '<a href="#" onclick="makevisible(\'presetstable\');" >Presets</a>'; }
if ($use_getfrom || $use_presets) echo "<br/>";

if ($use_getfrom) { 
	echo '<script type="text/javascript"> var theurl = "' .WB_URL.'/modules/'.$mod_dir.'/kram/getsettings.php?"; </script>';
	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="getfromtable" style="display:none;">
	<tr><td width="30%" valign="top">Get from:<br/>
	<form name="getsettings" action="#" method="get" style="margin: 0;">
	<select name="choosesettings" id="choosesettings" onchange="changesettings(this.options[this.selectedIndex].value);">'; 

	echo '<option value="page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">This one (reload)</option>';
			
	//Get other settings:
	$query_others = $database->query("SELECT page_id, section_id FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id <> '$section_id'  ORDER BY page_id ASC");
	if($query_others->numRows() > 0) { 	
		while($others = $query_others->fetchRow()) {
			$p_id = (int)$others['page_id'];
			$s_id = (int)$others['section_id'];
			$query_page = $database->query("SELECT menu_title, link FROM ".TABLE_PREFIX."pages WHERE page_id = '$p_id'");
			$fetch_menu = $query_page->fetchRow();
			$menutitle = $fetch_menu['menu_title'];
			$the_link = $fetch_menu['link'];
			echo '<option value="page_id='.$p_id.$paramdelimiter.'section_id='.$s_id.'">'.$menutitle .' (sid'.$s_id.')</option>';		
		}
	}
	
	echo '</select></form></td><td><div id="getfromdescription">NOTE: the get-from option will change the setting. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';
	
}



if ($use_presets) { 
	//get presets	
	$thelanguage = strtolower(LANGUAGE);
	if (!is_dir(WB_PATH.'/modules/'.$mod_dir.'/presets-'.$thelanguage)) { $thelanguage = 'en';}
	$presets_files = WB_PATH.'/modules/'.$mod_dir.'/presets-'.$thelanguage;
	echo '<script type="text/javascript"> var thelanguage = "' .$thelanguage. '"; </script>';

	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="presetstable" style="display:none;">
<tr><td width="30%" valign="top">Presets:<br/>

<form name="presets" action="#" method="get" style="margin: 0;">
<select name="choosepresets" id="choosepresets" onchange="changepresets(this.options[this.selectedIndex].value);">  
     <option value="">----------</option>';
	 
	$presets_dir = opendir($presets_files);				
	while ($file=readdir($presets_dir)) {
		if ($file != "." && $file != "..") {						
			if (ereg('.js',$file)) {
			$filename = substr($file, 0, -3);
			if ($filename == "default") continue;
			echo '<option value="'.$filename.'">'.$filename.'</option>'; 
			}
		}
	}
	echo '</select></form>
	</td><td><div id="presetsdescription">NOTE: the presets-option will change the setting. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';


 } 

echo '<hr/>';
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
$fetch_content = $query_content->fetchRow(); 

//Default:
$longusage1 = 0; $longusage2 = 0;
if ($html_allowed > 0) {$longusage1 = 1; $longusage2 = 1;}
if ($html_allowed > 1) {$longusage1 = 2; $longusage2 = 0;}
if ($html_allowed > 2) {$longusage1 = 2; $longusage2 = 2;}

if(!isset($fetch_content['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_members_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "various_values" added</h2>';
} else {
	if ($fetch_content['various_values'] != '') {
		$vv = explode(',',$fetch_content['various_values']);
		$longusage1 = (int) $vv[0];
		$longusage2 = (int) $vv[1];					
	}
}



?>

<form name="edit" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_settings.php" method="post" style="margin: 0;">

	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<strong><?php echo $METEXT['MNSETTINGS']; ?></strong>
<div id="settings1">
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
		
		<tr >
			<td width="30%" valign="top"><?php echo $METEXT['PIC_LOC']; ?>:</td>
			<td>
				<?php
				$pic_loc = stripslashes($fetch_content['pic_loc']);
				if ($pic_loc == '') { $pic_loc1 = '/members'; }
				?>
				<input name="pic_loc" type="text" value="<?php echo $pic_loc; ?>" style="width: 50%;" />
			</td>
		</tr>
		
		<tr >
		  <td valign="top"><?php echo $METEXT['EXTENSIONS']; ?>:</td>
		  <td><?php
		  
				$extensions = ''.stripslashes($fetch_content['extensions']);
				if ($extensions == '') { $extensions = $defaultextensions ; }				
				?>
				<input name="extensions" type="text" value="<?php echo $extensions; ?>" style="width: 50%;" /> 
			</td>       
	  	</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_GRP_BY']; ?>:</td>
			<td>
			<?php $sort_grp_name = stripslashes($fetch_content['sort_grp_name']); ?>
			
			<select name="sort_grp_name" style="width: 50%;">
				<option value ="0" <?php if ($sort_grp_name == 0) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_BY_ORDER']; ?></option>
				<option value ="1" <?php if ($sort_grp_name == 1) { echo ' selected="selected"';  } echo '>'.$METEXT['SORT_GRP_BY_NAME']; ?></option>
			</select>
		</td>
		</tr>
		<!-- delete_grp_members -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['DELETE_GRP_MEM']; ?>:</td>
			<td>
			<?php $delete_grp_members = stripslashes($fetch_content['delete_grp_members']); ?>
			
			<select name="delete_grp_members" style="width: 50%;">
				<option value="0" <?php if ($delete_grp_members == 0) { echo ' selected="selected"'; } echo '>'.$TEXT['NO']; ?></option>
				<option value="1" <?php if ($delete_grp_members == 1) { echo ' selected="selected"'; } echo '>'.$TEXT['YES']; ?></option>
			</select>
			</td>
		</tr>
		<!-- sort_mem_name -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_MEM_BY']; ?>:</td>
			<td>
			<?php $sort_mem_name = stripslashes($fetch_content['sort_mem_name']); ?>
			
			<select name="sort_mem_name" style="width: 50%;">
				<option value ="0" <?php if ($sort_mem_name == 0) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_BY_ORDER']; ?></option>
				<option value ="1" <?php if ($sort_mem_name == 1) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_BY_NAME']; ?></option>
				<option value ="2" <?php if ($sort_mem_name == 2) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_BY_SORTER']; ?></option>
				<option value ="3" <?php if ($sort_mem_name == 3) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_BY_SCORE']; ?></option>
			</select>
			</td>
		</tr>
		<!-- sort_mem_desc -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_ASC_DESC']; ?>:</td>
			<td>
			<?php $sort_mem_desc = stripslashes($fetch_content['sort_mem_desc']); ?>
			
			<select name="sort_mem_desc" style="width: 50%;">
				<option value ="0" <?php if ($sort_mem_desc == 0) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_ASC']; ?></option>
				<option value ="1" <?php if ($sort_mem_desc == 1) { echo ' selected="selected"'; } echo '>'.$METEXT['SORT_DESC']; ?></option>
			</select>
			</td>
		</tr>
		<!-- hide_email -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['HIDEMAIL']; ?>:</td>
			<td>
			<?php $hide_email = stripslashes($fetch_content['hide_email']); ?>
			
			<select name="hide_email" style="width: 50%;">
				<option value ="0" <?php if ($hide_email == 0) { echo ' selected="selected"'; } echo '>'.$TEXT['NO']; ?></option>
				<option value ="1" <?php if ($hide_email == 1) { echo ' selected="selected"'; } ?> >Javascript</option>
			</select>
			</td>
		</tr>

  </table>
  </div>
  <div id="settings2">
<hr />
<p><?php echo $METEXT['MODIFYFIELDS']; ?>:</p>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="options-names">
    <tr>
      <td width="30%">Short1<br/>
          <?php $t_short1 = stripslashes($fetch_content['t_short1']); ?>
          <input name="t_short1" type="text" value="<?php echo $t_short1; ?>" style="width: 80%;" />
      </td>
      <td width="30%">Long1<br/>
          <?php $t_long1 = stripslashes($fetch_content['t_long1']); ?>
          <input name="t_long1" type="text" value="<?php echo $t_long1; ?>" style="width: 90%;" />
      </td>
      <td><?php echo $METEXT['LONGUSAGE']; ?>:<br/>			
			<select name="longusage1">
				<option value ="0" <?php if ($longusage1 == 0) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_PLAIN']; ?></option>
				<option value ="1" <?php if ($longusage1 == 1) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_HTML']; ?></option>
				<option value ="2" <?php if ($longusage1 == 2) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_WYSIWYG']; ?></option>				
			</select>
		</td>
			
      <td>Memberpage ID<br/>
          <?php $t_memberpage_id = stripslashes($fetch_content['t_memberpage_id']); ?>
          <input name="t_memberpage_id" type="text" value="<?php echo $t_memberpage_id; ?>" style="width: 90%;" />
      </td>
    </tr>
    <tr>
      <td width="30%">Short2<br/>
          <?php $t_short2 = stripslashes($fetch_content['t_short2']); ?>
          <input name="t_short2" type="text" value="<?php echo $t_short2; ?>" style="width: 80%;" />
      </td>
      <td width="30%">Long2<br/>
          <?php $t_long2 = stripslashes($fetch_content['t_long2']); ?>
          <input name="t_long2" type="text" value="<?php echo $t_long2; ?>" style="width: 90%;" />
      </td>
      <td><?php echo $METEXT['LONGUSAGE']; ?>:<br/>			
			<select name="longusage2">
				<option value ="0" <?php if ($longusage2 == 0) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_PLAIN']; ?></option>
				<option value ="1" <?php if ($longusage2 == 1) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_HTML']; ?></option>
				<option value ="2" <?php if ($longusage2 == 2) { echo ' selected="selected"'; } echo '>'.$METEXT['LONGUSAGE_WYSIWYG']; ?></option>				
			</select>
		</td>
      <td>Link/Mail<br/>
          <?php $t_link = stripslashes($fetch_content['t_link']); ?>
          <input name="t_link" type="text" value="<?php echo $t_link; ?>" style="width: 90%;" />
      </td>
    </tr>
  </table>
  </div>
  <div id="settings3">
<hr />	
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
		<tr>
			<td colspan="2"><strong><?php echo $METEXT['LTSETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $TEXT['HEADER']; ?>:</td>
			<td><?php 
			$thefieldcontent = stripslashes(htmlspecialchars($fetch_content['header']));			
			$check = explode("\r\n", $thefieldcontent); 
			$thefieldcontentlines = count($check) + 1; if ($thefieldcontentlines < 3) {$thefieldcontentlines = 3;} if ($thefieldcontentlines > 20) {$thefieldcontentlines = 20;}			
			echo '<textarea name="header" id="membersheader" style="width: 98%; height: '.($thefieldcontentheight * $thefieldcontentlines).'px;">'.$thefieldcontent; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td><?php 
			$thefieldcontent = stripslashes(htmlspecialchars($fetch_content['footer']));			
			$check = explode("\r\n", $thefieldcontent); 
			$thefieldcontentlines = count($check) + 1; if ($thefieldcontentlines < 3) {$thefieldcontentlines = 3;} if ($thefieldcontentlines > 20) {$thefieldcontentlines = 20;}
			echo '<textarea name="footer" id="membersfooter" style="width: 98%; height: '.($thefieldcontentheight * $thefieldcontentlines).'px;">'.$thefieldcontent; ?></textarea>
			</td>
		</tr>
		<tr><td colspan="2"><hr/>
		</td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['GPHEADER']; ?></td>
			<td><?php 
			$thefieldcontent = stripslashes(htmlspecialchars($fetch_content['grp_head']));			
			$check = explode("\r\n", $thefieldcontent); 
			$thefieldcontentlines = count($check) + 1; if ($thefieldcontentlines < 3) {$thefieldcontentlines = 3;} if ($thefieldcontentlines > 20) {$thefieldcontentlines = 20;}
			echo '<textarea name="grp_head" id="membersgrp_head" style="width: 98%; height: '.($thefieldcontentheight * $thefieldcontentlines).'px;">'.$thefieldcontent; ?></textarea>			
		</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td><hr />
	      </td>
	  </tr>
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['TMLOOP']; ?></td>
			<td><?php 
			$thefieldcontent = stripslashes(htmlspecialchars($fetch_content['member_loop']));			
			$check = explode("\r\n", $thefieldcontent); 
			$thefieldcontentlines = count($check) + 1; if ($thefieldcontentlines < 3) {$thefieldcontentlines = 3;} if ($thefieldcontentlines > 20) {$thefieldcontentlines = 20;}
			echo '<textarea name="member_loop" id="membersmember_loop" style="width: 98%; height: '.($thefieldcontentheight * $thefieldcontentlines).'px;">'.$thefieldcontent; ?></textarea>			
		</td>
		</tr>
		<tr><td width="30%">&nbsp;</td><td><hr/></td></tr>
		<tr>
			<td width="30%" valign="top" class="newsection"><?php echo $METEXT['GPFOOTER']; ?></td>
			<td><?php 
			$thefieldcontent = stripslashes(htmlspecialchars($fetch_content['grp_foot']));			
			$check = explode("\r\n", $thefieldcontent); 
			$thefieldcontentlines = count($check) + 1; if ($thefieldcontentlines < 3) {$thefieldcontentlines = 3;} if ($thefieldcontentlines > 20) {$thefieldcontentlines = 20;}
			echo '<textarea name="grp_foot" id="membersgrp_foot" style="width: 98%; height: '.($thefieldcontentheight * $thefieldcontentlines).'px;">'.$thefieldcontent; ?></textarea>			
		</td>
		</tr>
		<tr>
			<td colspan="2" valign="top"><hr /></td>
		</tr>
  </table>
</div>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		  <td align="left">
		  	<input type="hidden" name="gobacktolist" id="gobacktolist" value="" />
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			<input name="save" type="submit" value="<?php echo $METEXT['SAVE_FINISH']; ?>" style="width: 150px; margin-top: 5px;" onclick="document.getElementById('gobacktolist').value = '1';"/>	
		</td>
			<td align="right">
                <input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
</form>

<?php
if ($use_presets == true) { echo '<p><a href="kram/getsettings.php?section_id='.$section_id.$paramdelimiter.'style=1" target="_blank">Open presets file</a></p>'; } 
echo "\n\n<!--stop Members modify_settings.php  code--> \n\n";
// Print admin footer
$admin->print_footer();

?>