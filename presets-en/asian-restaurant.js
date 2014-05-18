thepresetsdescription = 'Eine Speisekarte mit Zeichen-Nummern neben jedem Eintrag. Diese wird auch zur Sortierung verwendet.' ;

document.edit.pic_loc.value = '/members';
document.edit.extensions.value = 'gif jpg png jpeg';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 2);
selectDropdownOption (document.edit.sort_mem_desc, 0);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = '';
document.edit.t_link.value = '';
document.edit.t_short1.value = 'Preis\\e (durch \\ getrennt)';
document.edit.t_short2.value = 'Einheiten\\en  (durch \\ getrennt)';
document.edit.t_long1.value = '';
document.edit.t_long2.value = 'Beschreibung';
document.edit.header.value = '<!-- Asia Restaurant -->\n<!--Copy the styles to your stylesheet-->\n<style>\n.members-member td { padding-bottom: 10px;}\n.member-long2 {font-size: 0.9em; margin:0 0 10px 0; float:left; width:60%;}\n.member-short1, .member-short2 {\n	float:right;\n	font-size: 0.9em;\n	margin: 0 0 0 2px;\n	width:15%;\n	text-align: right;\n}\n.member-short1 {\n	font-weight: bold;\n}\n</style>		';
document.edit.footer.value = '<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n<p>[GROUPDESC]</p>\n</div>\n';
document.edit.member_loop.value = '<table width=\"98%\" class=\"members-member\">\n<tr valign=\"top\"><td width=\"40\">{SORTT}</td>\n<td align=\"left\" class=\"member-text\">\n<h3 class=\"member-name\"> [NAME]</h3>\n {LONG2}{LINK}{SHORT1}{SHORT2}</td></tr></table>\n';
document.edit.grp_foot.value = '<!-- Group Footer -->';

document.getElementById('presetsdescription').innerHTML = thepresetsdescription;
alert('Wu!');