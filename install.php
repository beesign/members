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

if(defined('WB_URL')) {
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members` ( '
					 . '`member_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`group_id` INT NOT NULL DEFAULT \'0\','
					 . '`position` INT NOT NULL DEFAULT \'0\','
					 . '`active` INT NOT NULL DEFAULT \'0\','
					 . '`m_isalias` INT NOT NULL DEFAULT \'0\','
					 . '`m_score` INT NOT NULL DEFAULT \'0\','
					 . '`m_sortt` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_name` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_memberpage_id` INT NOT NULL DEFAULT \'0\','
					 . '`m_link` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_short1` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_short2` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_long1` TEXT NOT NULL,'
 					 . '`m_long2` TEXT NOT NULL,'					 
					 . '`m_picture` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . 'PRIMARY KEY (member_id)'
                . ' )';
	$database->query($mod_members);

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members_groups`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members_groups` ( '
					 . '`group_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`section_id` INT NOT NULL DEFAULT \'0\','
					 . '`page_id` INT NOT NULL DEFAULT \'0\','
					 . '`position` INT NOT NULL DEFAULT \'0\','
					 . '`active` INT NOT NULL DEFAULT \'0\','					

					 . '`group_name` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`group_desc` TEXT NOT NULL DEFAULT \'\','

					 . '`group_cache` TEXT NOT NULL DEFAULT \'\','
					 . '`group_search` TEXT NOT NULL DEFAULT \'\','
					 . 'PRIMARY KEY (group_id)'
                . ' )';
	$database->query($mod_members);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members_settings`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members_settings` ( '
					 . '`section_id` INT NOT NULL DEFAULT \'0\','
					 . '`page_id` INT NOT NULL DEFAULT \'0\','
					 . '`t_memberpage_id` VARCHAR(255) NOT NULL DEFAULT \'0\','
					 . '`t_link` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_short1` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_short2` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_long1` VARCHAR(255) NOT NULL DEFAULT \'\','
 					 . '`t_long2` VARCHAR(255) NOT NULL DEFAULT \'\','		 
					 
					 . '`header` TEXT NOT NULL,'
					 . '`footer` TEXT NOT NULL,'
					 
					 . '`grp_head` TEXT NOT NULL,'
					 . '`grp_foot` TEXT NOT NULL,'
					 . '`member_loop` TEXT NOT NULL,'
					 . '`pic_loc` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`extensions` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`various_values` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`sort_grp_name` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . '`delete_grp_members` TINYINT(1) NOT NULL DEFAULT \'0\','					 
					 . '`sort_mem_name` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . '`sort_mem_desc` TINYINT(1) NOT NULL DEFAULT \'0\','

					 . '`hide_email` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . 'PRIMARY KEY (section_id)'
                . ' )';
	$database->query($mod_members);



	// Insert blank rows (there needs to be at least on row for the search to work)
	
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_groups (section_id,page_id,group_id) VALUES ('0', '0', '0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_settings (section_id,page_id) VALUES ('0', '0')");
	
	
	//______________________________________________

	$mod_dir = basename(dirname(__FILE__));

	//Add folder for images to media dir
	require_once(WB_PATH.'/framework/functions.php');
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/members');
	
	//Copy settings files
	$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
	if (!file_exists($mpath.'module_settings.php')) { copy($mpath.'kram/module_settings.default.php', $mpath.'module_settings.php') ; }
	if (!file_exists($mpath.'frontend.css')) { copy($mpath.'kram/frontend.default.css', $mpath.'frontend.css') ; }
	if (!file_exists($mpath.'frontend.js')) { copy($mpath.'kram/frontend.default.js', $mpath.'frontend.js') ; }
	
}

?>