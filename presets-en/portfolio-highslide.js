thepresetsdescription = 'Make a portflolio with Highslide. Read http://websitebaker.at/wb/module/members/portfolio.html';

document.edit.pic_loc.value = '/portfolio';
document.edit.extensions.value = 'gif jpg png jpeg';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 1);
selectDropdownOption (document.edit.sort_mem_desc, 0);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = '';
document.edit.t_link.value = 'Link and Linktext';
document.edit.t_short1.value = 'Short1';
document.edit.t_short2.value = 'Short2';
document.edit.t_long1.value = 'Description1 Long';
document.edit.t_long2.value = 'Description2 Long';
document.edit.header.value = '<link rel=\"stylesheet\" type=\"text/css\" href=\"[WB_URL]/modules/highslide/highslide.css\" />\n<script type=\"text/javascript\" src=\"[WB_URL]/modules/highslide/highslide.js\"></script>\n<script type=\"text/javascript\" src=\"[WB_URL]/modules/highslide/jquery.js\"></script>\n<script type=\"text/javascript\" src=\"[WB_URL]/modules/highslide/interface.js\"></script>\n\n<!-- \n    2) Optionally override the settings defined at the top\n    of the highslide.js file. The parameter hs.graphicsDir is important!\n-->\n<script type=\"text/javascript\">    \n    hs.graphicsDir = \'[WB_URL]/modules/highslide/graphics/\';\n    hs.outlineType = \'rounded-white\';\n</script>';
document.edit.footer.value = '<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n<p>[GROUPDESC]</p>\n</div>\n';
document.edit.member_loop.value = '<div class=\"portfolio\" style=\"padding-bottom:50px;\"><div class=\"marg\">\n<a id=\"thumb[MEMBER_ID]\" href=\"[MEDIA_DIRECTORY]/portfolio/view/[PICNAME]\" class=\"highslide\" onclick=\"return hs.expand(this)\" ><img src=\"[MEDIA_DIRECTORY]/portfolio/thumbs/[PICNAME]\" width=\"215\" height=\"133\" alt=\"[SHORT1]\" class=\"pfoto\" /></a>\n</div>\n<div class=\"portfolio_name\">[NAME]</div>\n{SHORT1}\n{LONG1} \n{SHORT2}\n{LONG2} \n{LINK}\n</div>\n';
document.edit.grp_foot.value = '<!-- Group Footer -->';
document.getElementById('getfromdescription').innerHTML = thepresetsdescription;
alert("Done");