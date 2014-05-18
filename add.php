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
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

	$mod_dir = basename(dirname(__FILE__));
	$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
	if (!file_exists($mpath.'module_settings.php')) { copy($mpath.'kram/module_settings.default.php', $mpath.'module_settings.php') ; }
	if (!file_exists($mpath.'frontend.css')) { copy($mpath.'kram/frontend.default.css', $mpath.'frontend.css') ; }
	if (!file_exists($mpath.'frontend.js')) { copy($mpath.'kram/frontend.default.js', $mpath.'frontend.js') ; }
	

// These are the default setting

require('kram/add_settings.default.php');
require('kram/module_settings.default.php');
require('module_settings.php');

$header = addslashes($header);
$footer = addslashes($footer);

$grp_head = addslashes($grp_head);
$grp_foot = addslashes($grp_foot);
$member_loop = addslashes($member_loop);

$hide_email = $hide_email_default;
$pic_loc = $pic_loc_default;

$t_memberpage_id= addslashes($t_memberpage_id);
$t_link = addslashes($t_link);
$t_short1 = addslashes($t_short1);
$t_short2 = addslashes($t_short2);
$t_long1 = addslashes($t_long1);
$t_long2 = addslashes($t_long2);



$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_settings (section_id, page_id, header, footer, grp_head, grp_foot, member_loop, hide_email, pic_loc, sort_mem_name, t_memberpage_id, t_link, t_short1, t_short2, t_long1, t_long2) VALUES ('$section_id', '$page_id', '$header', '$footer',  '$grp_head', '$grp_foot', '$member_loop', '$hide_email', '$pic_loc', '1', '$t_memberpage_id', '$t_link', '$t_short1', '$t_short2', '$t_long1', '$t_long2')");

?>