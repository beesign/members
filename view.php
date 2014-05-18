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
require(WB_PATH . '/modules/'.$mod_dir.'/kram/module_settings.default.php');
require(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');


// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
    }
}

// Load CSS file
// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/'.$mod_dir.'/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/'.$mod_dir.'/frontend.css');
   echo "\n</style>\n";
   
   echo '<script type="text/javascript">
		<!--
		function showmembermail(n,d,t) {
		var mail = n+\'@\'+d;
		if (t==\'\') {t = mail;}
		document.write(\'<a href=\"mailto:\'+ mail + \'\">\'+ t + \'</\'+\'a>\');
		} // -->
		</script>';
} 


// Get information on what groups and members are sorted by
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() <> 1) { die('No settings'); }
$settings_fetch = $query_settings->fetchRow();

$hide_email = (int)$settings_fetch['hide_email'];
$pic_loc = $settings_fetch['pic_loc'];
	
$sort_grp_name = $settings_fetch['sort_grp_name'];
if ($sort_grp_name == 1) {$sort_grp_by = "group_name";} else {$sort_grp_by = "position";}


//Handle long fields (text, html or wysiwyg)
//Default:
if ($html_allowed > 3) {$html_allowed = 3;}
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

$differentsorting = 0;
if(isset($_GET['sort']) AND is_numeric($_GET['sort'])) {
	$differentsorting = (int)$_GET['sort'];
	$use_caching = 0;
}
	





$alloutput = '';

// Loop through groups
$query_groups = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE section_id = '".$section_id."' AND active = '1' ORDER BY ".$sort_grp_by." ASC");

if($query_groups->numRows() > 0) {
	$header =  $settings_fetch['header'];
	$listgroup_links = 0;
	$listgroup_links_output = '';
	if ( strpos($header, '[LIST_GROUP_LINKS]') !== false) {$listgroup_links = 1;}
	if ( strpos($header, '[LIST_GROUP_LINKS_FULL]') !== false) {$listgroup_links = 2; $use_caching = 0;}
	
	$listmembers_links = 0;	
	if ( strpos($settings_fetch['grp_head'], '[LIST_MEMBERS_LINKS]') !== false) {$listmembers_links = 1;}
	
	
	
	
	while($group = $query_groups->fetchRow()) {
		$group_id = (int)$group['group_id'];
		
		//Cache
		$output = '';
		if ($use_caching == 1) {
			$query_content = $database->query("SELECT group_cache FROM ".TABLE_PREFIX."mod_members_groups WHERE group_id = '$group_id'");
			if($query_content->numRows() > 0) {
				$fetch_cache = $query_content->fetchRow();	
				$output = $fetch_cache['group_cache'];
				if (strlen($output) > 10) { 
					$vars = array( '[WB_URL]', '[PAGE_TITLE]', '[MENU_TITLE]', '[PAGES_DIRECTORY]', '[MEDIA_DIRECTORY]', '[LANGUAGE]' );
					$values = array (WB_URL, PAGE_TITLE, MENU_TITLE, PAGES_DIRECTORY, MEDIA_DIRECTORY, LANGUAGE);		
					$output = str_replace($vars, $values, $output);				
					$wb->preprocess($output);					
					//echo cache and continue, dont do the rest.
					$alloutput .= $output; continue;
				}
			}
		}
		
		//create output and maybe rebuild cache:
		$output = '';
		$listmembers_links_output = '';
		
		$m_groupname = $group['group_name']; $f_groupname='';				
		if ($m_groupname != '') { 
			$f_groupname = '<'.$block_tag.' class="mgroup-name">'.$m_groupname.'</'.$block_tag.'>'; 
			if ($listgroup_links == 1) {$listgroup_links_output .= '<li class="mgroup-list"><a href="#members_gr'.$group_id.'">'.$m_groupname."</a></li>\n";}
			if ($listgroup_links == 2) {$listgroup_links_output .= '<li class="mgroup-list"><a href="#members_gr'.$group_id.'">'.$m_groupname."</a>\n<ul>\n";}
		}
				
		$m_groupdesc = nl2br($group['group_desc']); $f_groupdesc='';
		if ($m_groupdesc != '') { $f_groupdesc = '<'.$block_tag.' class="mgroup-desc">'.$m_groupdesc.'</'.$block_tag.'>'; }

		
		
		
		// Sort member by m_score - m_sortt - m_name or position
		$sort_mem_name = $settings_fetch['sort_mem_name'];
		$sort_mem_desc = $settings_fetch['sort_mem_desc'];

		// Sorting members by m_score - m_sortt - m_name or position
		if ($sort_mem_desc == 1) {$sort_ad = ' DESC';} else {$sort_ad = ' ASC';}		
		
		if ($differentsorting != 0) { //different sorting by given parameter
			if ($differentsorting < 0) {
				$sort_ad = ' DESC';  				
				$sort_mem_name  = -1 - $differentsorting;
			} else {
				$sort_ad = ' ASC'; 
				$sort_mem_name  = $differentsorting - 1;
			}					
		}
		$reversesorting = 0 - $differentsorting;
		
		$sort_by = "position".$sort_ad;		
		// Sorting members by m_score - m_sortt - m_name or position
		if ($sort_mem_name == 1) {$sort_by = "m_name".$sort_ad;}
		if ($sort_mem_name == 2) {$sort_by = "m_sortt".$sort_ad.", m_name".$sort_ad;}
		if ($sort_mem_name == 3) {$sort_by = "m_score".$sort_ad.", m_sortt".$sort_ad.", m_name".$sort_ad;}

		// Query tem members in this group
		$query_members = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE group_id = '".$group_id."' AND active = '1' ORDER BY ".$sort_by );
		if($query_members->numRows() > 0) {			
			$rowcount = 0;
			// Loop through all members in this group		
			while($membersmember = $query_members->fetchRow()) {
				$member_id = (int)$membersmember['member_id'];				
				
				$m_name = $membersmember['m_name'];
				if ($listmembers_links == 1) {$listmembers_links_output .= '<li><a href="#members_num'.$member_id.'">'.$m_name.'</a></li>';}
				if ($listgroup_links > 1) {$listgroup_links_output .= '<li class="member-list"><a href="#members_num'.$member_id.'">'.$m_name.'</a></li>';}
				
				$the_score = (int)$membersmember['m_score'];
				if ($the_score > 0) { $m_score = ''.$the_score;  $f_score = '<div class="member-score">'.$m_score.'</div>'; } else {$m_score = ''; $f_score = '';}
				
				
				
				$isalias = (int)$membersmember['m_isalias'];
				if ($isalias == 0) {
					$the_member = $membersmember;
				} else {
					$query_alias = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$isalias."'");
					$the_member = $query_alias->fetchRow();					
				}
				
				$rowcount++;
				$mrow = $rowcount % 2;
				
				$m_sortt = $the_member['m_sortt']; $f_sortt='';				
				if ($m_sortt != '') { $f_sortt = '<'.$block_tag.' class="member-sortt">'.$m_sortt.'</'.$block_tag.'>'; }
				
				
				/* Aus member 0.7
				$m_short1 = $the_member['m_short1']; $f_short1='';
				if ($settings_fetch['t_short1'] == '') {$m_short1 = ''; $f_short1 = '';} else { 
					$m_short1 = stripslashes($m_short1); 
					if ($backslash_to_br) $m_short1 = str_replace('\\', '<br/>', $m_short1);
				}
				
				$m_long1 = $the_member['m_long1']; $f_long1='';
				if ($settings_fetch['t_long1'] == '') { $m_long1 = ''; $f_long1 = ''; } else { if ($longusage1 == 0) {  $m_long1 = nl2br(strip_tags($m_long1));} }
				if ($m_long1 != '') { $f_long1 = '<'.$block_tag.' class="member-long1">'.$m_long1.'</'.$block_tag.'>'; }
				*/
				
				
				$f_short1 = ''; $m_short1 = '';
				if ($settings_fetch['t_short1'] != '') {
					$m_short1 = stripslashes($the_member['m_short1']);
					//$m_short1 = $the_member['m_short1'];
					if ($backslash_to_br) $m_short1 = str_replace('\\', '<br/>', $m_short1);
					$f_short1 = '<'.$block_tag.' class="member-short1">'.$m_short1.'</'.$block_tag.'>';				
				}
				
				$f_long1 = ''; $m_long1 = '';
				if ($settings_fetch['t_long1'] != '') {
					//$m_long1 = stripslashes($the_member['m_long1']);
					$m_long1 = $the_member['m_long1'];
					if ($longusage1 == 0) {  $m_long1 = nl2br(strip_tags($m_long1));}
					$f_long1 = '<'.$block_tag.' class="member-long1">'.$m_long1.'</'.$block_tag.'>';				
				}
				
				$f_short2 = ''; $m_short2 = '';
				if ($settings_fetch['t_short2'] != '') {
					$m_short2 = stripslashes($the_member['m_short2']);
					//$m_short1 = $the_member['m_short2'];
					if ($backslash_to_br) $m_short2 = str_replace('\\', '<br/>', $m_short2);
					$f_short2 = '<'.$block_tag.' class="member-short2">'.$m_short2.'</'.$block_tag.'>';				
				}
				
				$f_long2 = ''; $m_long2 = '';
				if ($settings_fetch['t_long2'] != '') {
					//$m_long2 = stripslashes($the_member['m_long2']);
					$m_long2 = $the_member['m_long2'];
					if ($longusage1 == 0) {  $m_long2 = nl2br(strip_tags($m_long2));}
					$f_long2 = '<'.$block_tag.' class="member-long2">'.$m_long2.'</'.$block_tag.'>';				
				}
				
				
						
				//m_link: could be: Mail, Link or Text
				$m_link = $the_member['m_link']; $f_link ='';
				if ($m_link != '') { 
					require_once(WB_PATH.'/modules/'.$mod_dir.'/kram/functions.inc.php');
					$m_link = convert_member_link ($m_link, $hide_email);
					$f_link = '<'.$block_tag.' class="member-link">'.$m_link.'</'.$block_tag.'>';
				}
				
				//m_memberpage_id: must be a valid page_id
				$m_memberpage = '';	
				$f_memberpage = '';	
				$m_memberpage_id = (int)$the_member['m_memberpage_id'];
				if ($m_memberpage_id > 0) {
					$query_pages= $database->query("SELECT link, page_title FROM ".TABLE_PREFIX."pages WHERE page_id = '$m_memberpage_id'");
					if($query_pages->numRows() <> 1) { 
						$m_memberpage = ''; 
					} else {
						$pages_fetch = $query_pages->fetchRow();
						$memberpage_text = $pages_fetch['page_title'];											
						if ($use_caching == 1) {
							$m_memberpage = '[wblink'.$m_memberpage_id.']';
							$f_memberpage = '<'.$block_tag.' class="member-page"><a href="'.$m_memberpage.'">'.$memberpage_text.'</a></'.$block_tag.'>';
						} else {
							$pagelink = PAGES_DIRECTORY.$pages_fetch['link'].PAGE_EXTENSION;							
							$m_memberpage = WB_URL.$pagelink;							
							$f_memberpage = '<'.$block_tag.' class="member-page"><a href="'.$m_memberpage.'">'.$memberpage_text.'</a></'.$block_tag.'>';
						}					
					}					
				}
				
				$picname = ''.$the_member['m_picture'];
				if ($picname == '') { 
					$members_pic = WB_URL. '/modules/'.$mod_dir.'/img/nopic.jpg'; 
				} else {
					if ( strpos($picname, '://') !== false) {$members_pic = $picname;} else { $members_pic = WB_URL.''.MEDIA_DIRECTORY.''.$pic_loc . '/' . $picname; }
				}
				
				
				
				$vars = array( '[MEMBER_ID]', '[GROUP_ID]', '[IS_ALIAS]', '[PICNAME]', '[PICTURE]', '[NAME]', '[SCORE]','[SHORT1]', '[LONG1]', '[SHORT2]', '[LONG2]', '[LINK]', '[MEMBERPAGE]', '[SORTT]', '[ROWCOUNT]', '[MROW]', '{SCORE}','{SHORT1}', '{LONG1}', '{SHORT2}', '{LONG2}', '{LINK}', '{MEMBERPAGE}', '{SORTT}' );
				$values = array ($member_id, $group_id,  $isalias, $picname, $members_pic, $m_name, $m_score, $m_short1, $m_long1, $m_short2, $m_long2, $m_link, $m_memberpage, $m_sortt, $rowcount, $mrow, $f_score, $f_short1, $f_long1, $f_short2, $f_long2, $f_link, $f_memberpage, $f_sortt );
	
				//output:
				if ($listgroup_links > 1 OR $listmembers_links > 0) { $output .= '<a class="members_num_link" id="members_num'.$member_id.'" name="members_num'.$member_id.'"></a>';}					
				$output .= str_replace($vars, $values, $settings_fetch['member_loop']);
				
				//if ($listgroup_links == 2) {$listgroup_links_output .= "\n</ul>\n</li>\n";}
				
			}
		}
		if ($listgroup_links == 2) {$listgroup_links_output .= "\n</ul>\n</li>\n";}
		
		//Group Header
			
	
		$vars = array( '[GROUPNAME]', '[GROUPDESC]', '[GROUP_ID]', '{GROUPNAME}', '{GROUPDESC}' );
		$values = array ($m_groupname, $m_groupdesc, $group_id, $f_groupname, $f_groupdesc);		
		$groupheader = str_replace($vars, $values, $settings_fetch['grp_head']);
		if ($listmembers_links == 1) {
			$listmembers_links_output = '<ul>'.$listmembers_links_output.'</ul>';
			$groupheader = str_replace('[LIST_MEMBERS_LINKS]', $listmembers_links_output, $groupheader);
		}
		if ($listgroup_links > 0) { $groupheader = '<a class="members_group_link" id="members_gr'.$group_id.'" name="members_gr'.$group_id.'"></a>'.$groupheader;}
		//Group Output
		$output = $groupheader.$output.$settings_fetch['grp_foot'];
		//Some values will not be cached, because the might have been changed in the meantime.
		
		//Prepare str_replace
		$vars = array( '[WB_URL]', '[PAGE_TITLE]', '[MENU_TITLE]', '[PAGES_DIRECTORY]', '[MEDIA_DIRECTORY]', '[LANGUAGE]' );
		$values = array (WB_URL, PAGE_TITLE, MENU_TITLE, PAGES_DIRECTORY, MEDIA_DIRECTORY, LANGUAGE);
		
		//If we came here, the cache has to be rebuilt sometimes:
		
		//if one is logged in, we allways rebuild it; because most admins have a look at their pages before they log out.
		//So this will be the best way.
		if ($use_caching == 1 OR $wb->is_authenticated() ) { 
			if ($differentsorting == 0) { //dont change cache, if sorting is different
				$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache= '".$output."' WHERE group_id = '$group_id' ");
				
				//Prepare for search:
				$text = str_replace($vars, $values, $output);
				$text = strip_tags($text);
				$text = preg_replace('/\s+/', ' ', $text);			
				$text = addslashes($text);
				$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_search= '".$text."' WHERE group_id = '$group_id' ");
				echo "\n<!-- Cache rebuilt -->\n";
			}
		}
	
				
		$output = str_replace($vars, $values, $output);
		$wb->preprocess($output);
		$alloutput .= stripslashes($output);
		
	}
	
}

// Print header
//echo "<!-- HEADER START-->";
$header =  stripslashes($settings_fetch['header']);
$vars = array( '[WB_URL]', '[PAGE_TITLE]', '[MENU_TITLE]', '[PAGES_DIRECTORY]', '[MEDIA_DIRECTORY]', '[LANGUAGE]', '[REVERSESORTING]' );
$values = array (WB_URL, PAGE_TITLE, MENU_TITLE, PAGES_DIRECTORY, MEDIA_DIRECTORY, LANGUAGE, $reversesorting);
$header = str_replace($vars, $values, $header);

//Footer
$footer = stripslashes($settings_fetch['footer']);
$footer = str_replace($vars, $values, $footer);

if ($listgroup_links == 1) {
	$listgroup_links_output = '<ul>'.$listgroup_links_output.'</ul>';
	$header = str_replace('[LIST_GROUP_LINKS]', $listgroup_links_output, $header);
}
if ($listgroup_links == 2) {
	$listgroup_links_output = '<ul>'.$listgroup_links_output.'</ul>';
	$header = str_replace('[LIST_GROUP_LINKS_FULL]', $listgroup_links_output, $header);
}
		
echo $header;

echo $alloutput;

echo $footer;

?>