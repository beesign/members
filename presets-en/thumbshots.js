//Starting Javascript

thepresetsdescription = 'Links with thumbshots.org-Thumbnails';

document.edit.pic_loc.value = '';
document.edit.extensions.value = 'gif jpg png jpeg';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 0);
selectDropdownOption (document.edit.sort_mem_desc, 1);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = '';
document.edit.t_link.value = '';
document.edit.t_short1.value = 'Link (ohne http://)';
document.edit.t_short2.value = 'Attribute (normal:leer)';
document.edit.t_long1.value = 'Beschreibung';
document.edit.t_long2.value = '';
document.edit.header.value = '<!-- Module Header -->\n<h1>Links</h1>	';
document.edit.footer.value = '<p><a href=\"http://www.thumbshots.com\">Thumbnails powered by Thumbshots</a></p>\n<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n</div>\n';
document.edit.member_loop.value = '<div class=\"XXmembers-member\" style=\"width:50%; height:120px; float:left;\">\n<div style=\"width:120px;height:140px; float:left\"><a href=\"http://[SHORT1]\" target=\"_blank\" title=\"[SHORT1]\" [SHORT2]><img src=\"http://open.thumbshots.org/image.aspx?url=[SHORT1]\" width=\"120\" alt=\"[NAME]\" /></a></div>\n<div style=\"width:130px;float:left; line-height:120% ! important\" class=\"member-text\"  >\n<h3 class=\"member-name\"><a href=\"http://[SHORT1]\" target=\"_blank\" title=\"[SHORT1]\" [SHORT2]>[NAME]</a></h3>{LONG1}</div>\n</div>\n';
document.edit.grp_foot.value = '<hr style=\"clear:both\" /><!-- Group Footer -->';
document.getElementById('presetsdescription').innerHTML = thepresetsdescription;
alert('jepp!');