<?php
	$use_caching = 0; //0 or 1: caching speeds up the output, but can cause troubles.
	$use_aliases = 0; //0 or 1: Use the same member in different groups, even on different pages. See help, how it works.
	$use_presets = true; //on options-page: Use the javascript-presets
	$use_getfrom = true; //on options-page: get values from another section with javascript.
	
	
	$html_allowed = 2; 
	// 0: no HTML in fields. Special chars are converted with htmlspecialchars();
	// 1: You can use html. Chars will not get converted.
	// 2: one WYSIWYG-Editor, no HTML in all other fields (like 0)
	// 3: like 2, but 2 WYSIWYG-Editors
	
			
	$hide_email_default = 0; //0=Default, 1: Mail-adresses in field 'm_link' are hidden with Javascript.
	
	//Picture:
	$show_picture = 1; 
	$pic_loc_default = '/members'; //Default 
	$defaultextensions = 'gif jpg png jpeg';
	
	//the order of the fields on modify-member page: 
	$memberfieldorder = 'short1,long1,short2,long2,memberpage_id,link';
	
	$use_frontend_cssjs = 0; //0: no (default with WB 2.7 up), 1: link, 2: embed
	
	$noadmin_nooptions = 0; //0: no, 1: only the admin may change settings
	$paramdelimiter = '&'; //use '&amp;' in special cases
//-------------------------------------------------------------

//Various:
		
	$block_tag = 'div'; //Fields like {SHORT1} have an output with a complete tag and classes.
	$backslash_to_br = 1; //Short-fields only: Backslash \ gets <br/>
	
	$previewpic_wha =  'width="150" alt="preview"'; //attribute of the preview pic (modify-member)

?>
