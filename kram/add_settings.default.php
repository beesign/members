<?php	
	//Standardvorgaben für die Optionen:
	//Default settings in options:
	
	$t_memberpage_id= 'LinktoPage (page_id)';
	$t_link = 'Link or Mail';
	
	$t_short1 = 'Short Text 1';
	$t_short2 = 'Short Text 2';
	$t_long1 = 'Long Text 1'; 
	$t_long2 = 'Long Text 2';
	
	$header = '<!-- Module Header -->
	';
	$footer = '<!-- Module Footer -->
	';



$grp_head = '<div class="members-head">
<h2>[GROUPNAME]</h2>
<p>[GROUPDESC]</p>
</div>
';
$grp_foot = '<!-- Group Footer -->';

if ($show_picture = 1) {
$member_loop = '<table width="90%" class="members-member">
<tr valign="top"><td width="100">
<img src="[PICTURE]" width="100" alt="[NAME]" />{SCORE}</td>
<td align="left" class="member-text">
<h3 class="member-name">[NAME]</h3>
{SHORT1}{LONG1}{SHORT2}{LONG2}{LINK}{MEMBERPAGE}
</td></tr></table>
'; } else {
$member_loop = '<div class="members-member">
<h3 class="member-name">[NAME]</h3>
{SCORE}{SHORT1}{LONG1}{SHORT2}{LONG2}{LINK}{MEMBERPAGE}
';}



?>
