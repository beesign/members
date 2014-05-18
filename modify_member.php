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
if(!isset($_GET['member_id']) OR !is_numeric($_GET['member_id'])) {
  header("Location: ".ADMIN_URL."/pages/index.php");
} else {
  $member_id = (int) $_GET['member_id'];
 //$group_id = $_GET['group_id'];
}

//if (!$group_id) $group_id = 1;
$mod_dir = basename(dirname(__FILE__));
if (!defined('THEME_URL')) define ("THEME_URL", ADMIN_URL);

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



if (!isset($memberfieldorder)) {$memberfieldorder = 'short1,long1,short2,long2,memberpage_id,link';}
$memberfieldorder = strtolower($memberfieldorder);
$memberfieldorder = str_replace('  ','',$memberfieldorder);
$memberfieldorder = str_replace(' ','',$memberfieldorder);

$memberfieldorderarray = explode(',',$memberfieldorder);
if (!in_array('short1', $memberfieldorderarray)) {$memberfieldorderarray[] = 'short1';}
if (!in_array('long1', $memberfieldorderarray)) {$memberfieldorderarray[] = 'long1';}
if (!in_array('short2', $memberfieldorderarray)) {$memberfieldorderarray[] = 'short2';}
if (!in_array('long2', $memberfieldorderarray)) {$memberfieldorderarray[] = 'long2';}
if (!in_array('memberpage_id', $memberfieldorderarray)) {$memberfieldorderarray[] = 'memberpage_id';}
if (!in_array('link', $memberfieldorderarray)) {$memberfieldorderarray[] = 'link';}


if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("m_long1","m_long2");
	require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '$member_id'");
$fetch_content = $query_content->fetchRow();

$isalias = (int)$fetch_content['m_isalias'];
$group_id = (int)$fetch_content['group_id'];
$from = 0;
if(isset($_GET['from']) AND is_numeric($_GET['from'])) { $from = (int) $_GET['from']; } 


//the Group Selection box:
$m_selection = '<select style="width:150px;" name="newgroup"><option value="1">'. $TEXT['NONE'].'</option>';
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE page_id = $page_id ORDER BY position ASC");				
if($query->numRows() > 0) {
	$linestyle=' topline';	
	// Loop through groups
	while($group = $query->fetchRow()) {
		$gid = $group['group_id'];									
		if ( $gid  < 2) {continue;}
		$m_selection .=  '<option value="'.$gid.'"'; 
		if ($gid  == $group_id) { 						
			$m_selection .=  ' class="thismember'.$linestyle . '" selected>'.$group['group_name']."</option>\n";
		} else {
			$m_selection .=  ' class="thisgroup'.$linestyle . '">'. $group['group_name']."</option>\n";
		}
	$linestyle='';
	}					
}
				
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE page_id <> $page_id ORDER BY position ASC");				
if($query->numRows() > 0) {				
	// Loop through groups
	$linestyle=' topline';
	while($group = $query->fetchRow()) {									
		if ($group['group_id'] < 2) {continue;}
		$m_selection .=   '<option value="'.$group['group_id'].'" class="otherpage'.$linestyle.'">'. $group['group_name']."</option>\n";
		$linestyle='';
	}
}

$m_selection .= '</select>';


$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() > 0) {
	$settings_fetch = $query_settings->fetchRow();
	$pic_loc = $settings_fetch['pic_loc'];
				
	$listextensions = $settings_fetch['extensions'];
	if (''.$listextensions=='') {
		$listextensions = ".gif|.GIF|.jpg|.JPG|.png|.PNG|.jpeg|.JPEG";
	} else {
		$learray = explode(' ', $listextensions);
		$listextensions = '';
		foreach ($learray as $ext) {
			$listextensions .= '|.'.$ext.'|.'.strtoupper($ext);
		}
		$listextensions = substr($listextensions, 1, strlen($listextensions));				
	}								
	$sort_mem_name = $settings_fetch['sort_mem_name'];
}

//Field usage default:
$longusage1 = 0; $longusage2 = 0;
if ($html_allowed > 0) {$longusage1 = 1; $longusage2 = 1;}
if ($html_allowed > 1) {$longusage1 = 2; $longusage2 = 0;}
if ($html_allowed > 2) {$longusage1 = 2; $longusage2 = 2;}

if(!isset($settings_fetch['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_members_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "various_values" added</h2>';
} else {
	if ($settings_fetch['various_values'] != '') {
		$vv = explode(',',$settings_fetch['various_values']);
		$longusage1 = (int) $vv[0];
		$longusage2 = (int) $vv[1];					
	}
}



?>
<!--Start Members modify_member.php  code-->
<div class="mod_members">
<form name="modify" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_member.php" method="post" style="margin: 0;">
	<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
	<input type="hidden" name="isalias" value="<?php echo $isalias; ?>">
	<input type="hidden" name="from" value="<?php echo $from; ?>">
	
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr valign="top">
	<td width="160" class="lefttd">
	<div style="margin-bottom:10px; width:150px;"><?php echo $TEXT['GROUP']; ?>:<br/>
				<?php echo $m_selection;?>				
	  	</div>
	
	<?php 
	//sortt & sortv 
	//See Note#1 for details
	if ($isalias  == 0 AND $sort_mem_name > 0) {
	//m_sortt: Sort by Text	
		echo '<div id="sortt">'.$METEXT['M_SORT_T'].'<br/>';
	 	echo '<input type="text" name="m_sortt" value="'.stripslashes($fetch_content['m_sortt']).'" style="width:90px;" maxlength="7" /><br/>
		'.$METEXT['SORTERHELP'].'</div>';
	} else {
		echo '<input type="hidden" name="m_sortt" value="'.stripslashes($fetch_content['m_sortt']).'">';
	}
	//Both Members and Alias can have a own score:
	if ($sort_mem_name == 3) {
		echo '<div id="m_score">'.$METEXT['M_SORT_V'].'<br/>';
	 	echo '<input type="text" name="m_score" value="'.$fetch_content['m_score'].'" style="width:90px;" maxlength="10" /></div>';	
	} else {
		echo '<input type="hidden" name="m_score" value="'.$fetch_content['m_score'].'">';
	}
	
	
	
	//-------------------------------------------------------------
	// the picture:
	
	if ($pic_loc <> "") {
	
		if ($isalias  > 0) {
		//is an alias:					
			$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$isalias."'");				
			if($query->numRows() <> 1) { 
				//Maybe delete alias here and then die??
				die("Error: No such member");
			}		
			$aliasof = $query->fetchRow();	
			$picfile = $aliasof['m_picture'];
			if ($picfile == "" OR $pic_loc == "") { $previewpic =  WB_URL . '/modules/'.$mod_dir.'/img/nopic.jpg'; } else { $previewpic =  WB_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/'.$picfile; }			  
			if ( strpos($picfile, '//') !== false) {$previewpic = $picfile;}
			echo '<img src="'.$previewpic.'" '.$previewpic_wha.' name="memberpic" id="memberpic" />';
		} else {		
			//is NO alias, get picture selection:
			echo $TEXT['IMAGE'].":"; 
			// this piece of code scans the given directory and creates the selector
			  
			if ($pic_loc == "") { $file_dir = "";} else { $file_dir= WB_PATH.'/'.MEDIA_DIRECTORY.'/'.$pic_loc; }
			$picfile = $fetch_content['m_picture'];
			if ($picfile == "" OR $pic_loc == "") { $previewpic =  WB_URL.'/modules/'.$mod_dir.'/img/nopic.jpg'; } else { $previewpic =  WB_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/'.$picfile; }
			
			if ($show_picture == 2) { //Options
				$check_pic_dir=is_dir("$file_dir");
				if ($check_pic_dir=='1') {
					$pic_dir=opendir($file_dir);
				
					echo '<select style="width:150px;" name="m_picture" onChange="javascript:changepic()">'."\n";
					echo "<option value=\"\">None selected</option>\n";
				
					while ($file=readdir($pic_dir)) {
						if ($file != "." && $file != "..") {						
			    			if (ereg($listextensions,$file)) {
			        			echo "<option value=\"".$file."\"";
								if($picfile == $file) { echo " Selected"; } 
								echo ">".$file."</option>\n"; 
							}
						}
					}
					echo "</select>\n";				
				} else {
					echo $METEXT['DIRECTORY'].$pic_loc.$METEXT['NOT_EXIST']; 
				}
			}
			
			if ($show_picture == 1) { //AJAX
			
			echo '<input type="text" style="width:150px;" value="'.$picfile.'" name="m_picture" id="m_picture" onChange="javascript:changepic()" />'; 
			//echo '<div class="topicpic_container"><img src="'.$previewpic.'" name="memberpic" id="memberpic" alt="" /></div>';
			echo '<p><b><a href="javascript:openpicturepreviews();">'.$METEXT['OPENPICTABLE'].'</a></b></p>';
		}
			
			
			if ( strpos($picfile, '//') !== false) {$previewpic = $picfile;}
			echo '<img src="'.$previewpic.'" '.$previewpic_wha.' name="memberpic" id="memberpic" />';
				
		} //end is NO Alias
	} // end if ($pic_loc <> "")
	
	?>
	
	</td><td>
	
	<?php
	//------------------------------------------------------------------------------
	//Right Block
	//------------------------------------------------------------------------------
	
	$the_div = '<div style="margin-bottom:10px;">';
	$the_divend = '" style="width: 99%;" maxlength="255" /></div>'."\n";
	
	if ($isalias  > 0) {
	//________________________________________________
		//Is Alias:
		echo '<input type="hidden" name="m_name" value="'.$aliasof['m_name'].'"/>';
		
		echo '<h2>'.$METEXT['IS_ALIAS_OF'].'</h2>';
		echo $the_div.$aliasof['m_name'].'</div>';
	
	
		foreach ($memberfieldorderarray as $posfieldname) {
		
			if ($posfieldname=='short1') {
				$m_short1 = stripslashes($aliasof['m_short1']);		
				if ($m_short1 <> "") {echo $the_div.stripslashes($settings_fetch['t_short1']).':<br/>'.stripslashes($aliasof['m_short1']).'</div>';}
			}
			if ($posfieldname=='long1') {
				$m_long1 = stripslashes($aliasof['m_long1']);				
				if ($m_long1 <> "") {echo $the_div.stripslashes($settings_fetch['t_long1']).':<br/>'.stripslashes($aliasof['m_long1']).'</div>';}		
			}
			if ($posfieldname=='short2') {
				$m_short2 = stripslashes($aliasof['m_short2']);		
				if ($m_short2 <> "") {echo $the_div.stripslashes($settings_fetch['t_short2']).':<br/>'.stripslashes($aliasof['m_short2']).'</div>';}
			}
			if ($posfieldname=='long2') {
				$m_long2 = stripslashes($aliasof['m_long2']);		
				if ($m_long2 <> "") {echo $the_div.stripslashes($settings_fetch['t_long2']).':<br/>'.stripslashes($aliasof['m_long2']).'</div>';}
			}
			if ($posfieldname=='memberpage_id') {
				$m_memberpage_id = stripslashes($aliasof['m_memberpage_id']);		
				if ($m_memberpage_id > 0 ) {echo $the_div.stripslashes($settings_fetch['t_memberpage_id']).': '.stripslashes($aliasof['m_memberpage_id']).'</div>';}
			}
			if ($posfieldname=='link') {
				$m_link = stripslashes($aliasof['m_link']);		
				if ($m_link <> "") {echo $the_div.stripslashes($settings_fetch['t_link']).': '.stripslashes($aliasof['m_link']).'</div>';}
			}
		}
	
	} else { 
	//________________________________________________
	//is NO alias:
	
		$html_spch = 0;
		echo $the_div.$TEXT['NAME'].'<br/><input type="text" name="m_name" value="';
		$t = stripslashes($fetch_content['m_name']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
		
		foreach ($memberfieldorderarray as $posfieldname) {
		
			if ($posfieldname=='short1') {
				if ($settings_fetch['t_short1'] <> '') { 
					echo $the_div.stripslashes($settings_fetch['t_short1']).':<br/><input type="text" name="m_short1" value="';
					$t = stripslashes($fetch_content['m_short1']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
				}
			}
			if ($posfieldname=='long1') {
				if ($settings_fetch['t_long1'] <> '') { 
					$t = stripslashes($fetch_content['m_long1']);
					if ($longusage1 > 1) { //use the wysiwyg Editor
						show_wysiwyg_editor("m_long1","m_long1",htmlspecialchars($t),"100%","235px"); echo '<p>&nbsp;</p>';
					} else { // use a simple area:
						echo $the_div.stripslashes($settings_fetch['t_long1']).':<br/><textarea name="m_long1" style="width:99%; height: 80px;">';
						if ($longusage1 == 1) {$t = htmlspecialchars($t);} echo $t.'</textarea></div>'; 
					}
				}
			}
			if ($posfieldname=='short2') {
				if ($settings_fetch['t_short2'] <> '') { 
					echo $the_div.stripslashes($settings_fetch['t_short2']).':<br/><input type="text" name="m_short2" value="';
					$t = stripslashes($fetch_content['m_short2']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
				}
			}
			if ($posfieldname=='long2') {
				if ($settings_fetch['t_long2'] <> '') { 
					$t = stripslashes($fetch_content['m_long2']);
					if ($longusage2 > 1) { //use the wysiwyg Editor
						show_wysiwyg_editor("m_long2","m_long2",htmlspecialchars($t),"100%","235px"); echo '<p>&nbsp;</p>';
					} else { // use a simple area:
						echo $the_div.stripslashes($settings_fetch['t_long2']).':<br/><textarea name="m_long2" style="width:99%; height: 80px;">';
						if ($longusage2 == 1) {$t = htmlspecialchars($t);} echo $t.'</textarea></div>'; 
					}
				}
			}
			if ($posfieldname=='link') {
				if ($settings_fetch['t_link'] <> '') { echo $the_div.stripslashes($settings_fetch['t_link']).':<br/><input type="text" name="m_link" value="'.stripslashes($fetch_content['m_link']).$the_divend; }
			}
			
			if ($posfieldname=='memberpage_id') {
				if ($settings_fetch['t_memberpage_id'] <> '') { 
					//Check, if valid
					$memp = (int)stripslashes($fetch_content['m_memberpage_id']);
					$thememp = '';
					if ($memp > 0) {
						$query_pages = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '$memp'");
						if($query_pages->numRows() == 1) {
							$fetch_pages = $query_pages->fetchRow();
							$thememp = '(found: '.$fetch_pages['link'].PAGE_EXTENSION.')';				
				 		} else {
				 			$thememp = '<span style="color:red">page_id <b>'.$memp.'</b> not found</span>';
				 		}			
					}						
					echo "\n".$the_div.stripslashes($settings_fetch['t_memberpage_id']).':&nbsp;<input type="text" name="m_memberpage_id" style="width:30px;" maxlength="4" onchange="JavaScript:xmlhttpPostLink(\'kram/getmemberpagelink.php\')" value="'.$memp.'" /><span id="memberpagelink" style="margin-left:10px">'.$thememp.'</span></div>'."\n"; 
				}
			}	
		} //end foreach
		//echo '<p>'; if ($html_allowed != 1) {echo $METEXT['HTMLNOTALLOWED']; } else {echo $METEXT['HTMLALLOWED']; } echo '</p>';		
	} //end is NO alias
	
	// Members AND Alias:
	echo '<div>'.$TEXT['ACTIVE']; ?>:<br/>
	<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked'; } ?> />
	<a href="javascript: toggle_checkbox('active_true');"><?php echo $TEXT['YES']; ?></a>&nbsp;
	<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked'; } ?> />
	<a href="javascript: toggle_checkbox('active_false');"><?php echo $TEXT['NO']; ?></a>
	</div>
		
	<?php if ($pic_loc <> "") { echo '<script type="text/javascript">'."\n";
				echo 'var memberpicloc = "'.WB_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/"'."\n";
				echo 'var membergroup = '.$group_id."\n"; } ?>
	</script> 
		
		
		
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input type="hidden" name="gobacktolist" id="gobacktolist" value="0" />
			<input type="hidden" name="duplicate" id="doduplicate" value="0" />
	  		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			<input name="save" type="submit" value="<?php echo $METEXT['SAVE_FINISH']; ?>" style="width: 150px; margin-top: 5px;" onclick="document.getElementById('gobacktolist').value = '1';"/>
			<input name="save" type="submit" value="<?php echo $METEXT['SAVE_DUPLICATE']; ?>" style="width: 100px; margin-top: 5px; margin-left:50px" onclick="document.getElementById('doduplicate').value = '1';"/>
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


</td></tr></table>
<div id="picturechooser"></div>
</div>



<script type="text/javascript">
	var membersection = <?php echo $section_id; ?>;
	changepic();
	
</script>

<!--Stop Members modify_member.php  code-->
		
<?php

// Print admin footer
$admin->print_footer();

?>