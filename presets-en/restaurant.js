thepresetsdescription = 'Eine klassische Speisekarte mit Bildern.';

document.edit.pic_loc.value = '/members';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 0);
selectDropdownOption (document.edit.sort_mem_desc, 0);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = '';
document.edit.t_link.value = '';
document.edit.t_short1.value = 'Preis/e';
document.edit.t_short2.value = 'Einheiten/en';
document.edit.t_long1.value = '';
document.edit.t_long2.value = 'Beschreibung';
document.edit.header.value = '<!-- Speisekarte -->\n<style>\n/*speisekarte*/\n.members-member td { padding-bottom: 10px;}\n.member-long2 {font-size: 0.9em; margin:0 0 10px 0; float:left; width:70%;}\n.member-short1, .member-short2 {\n	float:right;\n	font-size: 0.9em;\n	margin: 0;\n	width:10%;\n	text-align: right;\n}\n.member-short1 {\n	font-weight: bold;\n}\n/*Ranking*/\n.top10ranking {font-size: 0.8em;}\n.member-score {\n	font-size: 36px;\n	font-weight: bold;\n	color: #009999;\n}\n</style>	';
document.edit.footer.value = '<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n<p>[GROUPDESC]</p>\n</div>\n';
document.edit.member_loop.value = '<table width=\"98%\" class=\"members-member row[MROW]\" id=\"mem[MEMBER_ID]\">\n<tr valign=\"top\"><td width=\"80\">\n<img src=\"[PICTURE]\" width=\"50\" height=\"50\" alt=\"\" /></td>\n<td align=\"left\" class=\"member-text\">\n<h3 class=\"member-name\">[ROWCOUNT]. [NAME]</h3>{LONG2}{SHORT1}{SHORT2}</td></tr></table>\n';
document.edit.grp_foot.value = '<!-- Group Footer -->';

document.getElementById('presetsdescription').innerHTML = thepresetsdescription;
alert('jepp!');