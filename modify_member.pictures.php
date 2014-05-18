<?php

// $Id: modify_topic.php 660 2008-02-02 13:51:46Z thorn $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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

$starttime = array_sum(explode(" ",microtime()));

// Get id
if(!isset($_POST['s']) OR !is_numeric($_POST['s'])) {
  die();
} else {
  $section_id = $_POST['s'];  
}



//$section_id=72;

// Include config file
require_once(dirname(__FILE__).'/../../config.php');

// Check if the config file has been set-up
if(!defined('WB_PATH')) {
	header("Location: install/index.php");
	exit(0);
}
/*
define('PAGE_ID',0);
define('PARENT',0);
require_once(WB_PATH.'/framework/class.frontend.php');
// Create new frontend object
$wb = new frontend();

// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require(WB_PATH.'/framework/frontend.functions.php');
*/

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Modules', 'module_view', false, false);
if (!($admin->is_authenticated() && $admin->get_permission('member', 'module')))  {
	die("Sorry, no access!"); 
} else {

	$mod_dir = basename(dirname(__FILE__));
	// Load Language file
	if(LANGUAGE_LOADED) {
		if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
		} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
		}
	}


	require_once(WB_PATH.'/modules/'.$mod_dir.'/kram/module_settings.default.php');
	require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');

	// Get Settings
	$query_settings = $database->query("SELECT pic_loc,extensions FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
	$fetch_settings = $query_settings->fetchRow();
	$picture_dir = MEDIA_DIRECTORY.$fetch_settings['pic_loc'];
	echo '<table class="choosertable" border="0" cellpadding="0" cellspacing="0"><tr class="r1"> <td class="c1"><img src="img/shadow/shadow_nw.png" alt="" /></td><td class="c2"><a href="javascript:choosethispicture(0);"><img src="img/closebox.png" alt="close" /></a></td><td class="c3"><img src="img/shadow/shadow_ne.png" alt="" /></td></tr><tr class="r2"><td class="c1">&nbsp;</td>
<td class="inner"><div class="innerheader"></div><div id="picturechooserinner">';
	
	if ($picture_dir != '') {
		
		$file_dir= WB_PATH.''.$picture_dir;
				
		$check_pic_dir=is_dir("$file_dir");
		$allpreviews = '';
		if ($check_pic_dir=='1') {
			$pic_dir=opendir($file_dir);
			$picextensions = ".gif|.GIF|.jpg|.JPG|.png|.PNG|.jpeg|.JPEG";
			
			$listextensions = $fetch_settings['extensions'];
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
			
			
			
			while ($file=readdir($pic_dir)) {
				if ($file != "." && $file != "..") {
					if (ereg($listextensions,$file)) {						
						if (ereg($picextensions,$file)) {
							$thepreview = '<div class="memberpic_preview"><a href="javascript:choosethispicture(\''.$file.'\');"><img src="'.WB_URL.$picture_dir.'/'.$file.'" alt="" /></a></div>';
						} else {
							$showfile = str_replace('-',' ',$file); 
							$showfile = str_replace('_',' ',$showfile);
							$thepreview = '<div class="memberpic_preview"><a href="javascript:choosethispicture(\''.$file.'\');">'.$showfile.'</a></div>';	
						} 
						$allpreviews = $thepreview.$allpreviews;
					}
				}
			}
			echo $allpreviews;			
		} else {
			echo $METEXT['DIRECTORY'].$picture_dir.$METEXT['NOT_EXIST']; 
		}
	
	} else { 
		echo $METEXT['DIRECTORY'].$picture_dir.$METEXT['NOT_EXIST']; 
	}
	
	echo '</div></td>
<td class="c3">&nbsp;</td></tr><tr class="r3"><td class="c1"><img src="img/shadow/shadow_sw.png" alt="" /></td><td class="c2">&nbsp;</td><td  class="c3"><img src="img/shadow/shadow_se.png" alt="" /></td></tr></table>';

	
} 
?>