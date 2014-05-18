thepresetsdescription = 'Teilnehmer bei einem Turnier';

document.edit.pic_loc.value = '/members';
document.edit.extensions.value = 'gif jpg png jpeg';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 3);
selectDropdownOption (document.edit.sort_mem_desc, 1);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = '';
document.edit.t_link.value = '';
document.edit.t_short1.value = 'Verein';
document.edit.t_short2.value = '';
document.edit.t_long1.value = '';
document.edit.t_long2.value = '';
document.edit.header.value = '<!-- Speisekarte -->\n<style>\n/*Turnier*/\n.members-member td { padding: 3px;}\n.scoretr {color: #666666}\n.scoretr1 {color: #009966}\n.mscore1  {color: #ffffff}\n\n.r1 {background-color: #CCFFCC;}\n.r2 {background-color: #ddFFee;}\n</style>	';
document.edit.footer.value = '<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n<p>[GROUPDESC]</p>\n</div>\n<table width=\"98%\" class=\"members-member\">';
document.edit.member_loop.value = '<tr valign=\"top\" class=\"scoretr[SCORE] h[ROWCOUNT]\">\n<td class=\"hscore[SCORE]\" width=\"10%\" >[SCORE]</td>\n<td class=\"mname\" width=\"40%\" >[NAME]</td>\n<td>[SHORT1]</td>\n';
document.edit.grp_foot.value = '</tr></table>\n<!-- Group Footer -->';

document.getElementById('presetsdescription').innerHTML = thepresetsdescription;
alert('jepp!');