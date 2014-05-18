<?php
//Delete all links and groups with no m_name
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members  WHERE m_name=''");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members_groups  WHERE group_id > '1' AND group_name=''");

//Clear cache:
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache= ''");
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_search= ''");

// Loop through existing groups
$query_groups = $database->query("SELECT group_id FROM `".TABLE_PREFIX."mod_members_groups`");
if($query_groups->numRows() > 0) { 

	$all_groups = array();
	while($group_fetch = $query_groups->fetchRow()) {
		$gid = $group_fetch['group_id'];	
		$all_groups[] = $gid;
	}
}

// Loop through existing members
$query_members = $database->query("SELECT member_id,group_id FROM `".TABLE_PREFIX."mod_members`");
if($query_members->numRows() > 0) {

	// check, if the group exist:
	while($member_fetch  = $query_members->fetchRow()) {
		$mg = $member_fetch['group_id'];	
		if (in_array($mg, $all_groups)) { 
			continue; 
		} else {
		$member_id = $member_fetch['member_id'];
		echo "<br/>not found: ".$member_id. " in ".$member_fetch['group_id'];
		$database->query("UPDATE ".TABLE_PREFIX."mod_members SET group_id = '1' WHERE member_id = '$member_id'");
		}	
	}
}
?>