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

// Get id
if(!isset($_GET['group_id']) OR !is_numeric($_GET['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_GET['group_id'];
}
$from = 0;
if(isset($_GET['from']) AND is_numeric($_GET['from'])) { $from = (int) $_GET['from']; } 

$mod_dir = basename(dirname(__FILE__));
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

require('kram/module_settings.default.php');
include('module_settings.php');




if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}
if (!defined('THEME_URL')) define ("THEME_URL", ADMIN_URL);

//prepare WYSIWYG_EDITOR
if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("m_long1","m_long2");
	require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}



// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE group_id = '$group_id'");
$fetch_content = $query_content->fetchRow();
$g_name = stripslashes($fetch_content['group_name']);
$g_desc = stripslashes($fetch_content['group_desc']);

?>
<!--Start Members modify_group.php  code-->
<div class="mod_members">
<form name="modify" action="<?php echo WB_URL.'/modules/'.$mod_dir.'/save_group.php"'; ?> method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
<input type="hidden" name="from" value="<?php echo $from; ?>">

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="90"><?php echo $TEXT['NAME']; ?>:</td>
	<td>
		<input type="text" name="group_name" value="<?php echo $g_name; ?>" style="width: 100%;" maxlength="255" />
	</td>
</tr>
<tr valign="top">
			<td width="80"><?php echo $TEXT['DESCRIPTION']; ?>:</td>
			<td>
			<?php
			if ($html_allowed > 1) { //use the wysiwyg Editor
				show_wysiwyg_editor("group_desc","group_desc",htmlspecialchars($g_desc),"100%","450px");
			} else { // use a simple area:
			 	echo '<textarea name="group_desc" style="width:99%; height: 80px;">'.$g_desc.'</textarea>
			 	<p>';
			}			
			if ($html_allowed == 1)  {echo $METEXT['HTMLALLOWED']; } 
			?>
				<br/></p>
			</td>
		</tr>
<tr>
	<td><?php echo $TEXT['ACTIVE']; ?>:</td>
	<td>
		<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
		<?php echo $TEXT['YES']; ?>
		</a>
		-
		<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
		<?php echo $TEXT['NO']; ?>
		</a>
	</td>
</tr>

</table>

<br />

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input type="hidden" name="gobacktolist" id="gobacktolist" value="" />
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
		<input name="save" type="submit" value="<?php echo $METEXT['SAVE_FINISH']; ?>" style="width: 150px; margin-top: 5px;" onclick="document.getElementById('gobacktolist').value = '1';"/>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php 
		$back_url =  ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
		if ($from == 2) { $back_url = WB_URL.'/modules/'.$mod_dir .'/modify_sort.php?page_id='.$page_id.'&section_id='.$section_id; }
		echo $back_url; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>
</form>
</div>
<?php

// Print admin footer
$admin->print_footer();

?>