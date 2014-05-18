<?php

######################################################################################################################
#
#	PURPOSE OF THIS FILE:
#	This file contains the routines required to edit the optional module files: frontend.css and backend.css.
# Nothing needs to be changed in this file. Keep it as is.
#
#	INVOKED BY:
#	This file should be invoked by clicking on a text link shown in modify.php.
#
######################################################################################################################

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (C) year, Authors name
  Contact me: author(at)domain.xxx, http://authorwebsite.xxx

  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
  GNU General Public License for more details.
**/

// include configuration file and admin wrapper script
require('../../config.php');
require(WB_PATH.'/modules/admin.php');

$mod_dir = basename(dirname(__FILE__));
require('kram/module_settings.default.php');
include('module_settings.php');

// include functions to edit the optional module CSS files (frontend.css, backend.css)
require_once('css.functions.php');

// check if action is: save or edit
if(isset($_GET['action']) && $_GET['action'] == 'save' && 
		isset($_POST['edit_file']) && mod_file_exists($_POST['edit_file'])) {
	/** 
		SAVE THE UPDATED CONTENTS TO THE CSS FILE
	*/

	$css_content = '';
	if(isset($_POST['css_codepress']) && strlen($_POST['css_codepress']) > 0) {
		// Javascript is enabled so take contents from hidden field: css_codepress
		$css_content = stripslashes($_POST['css_codepress']);
	} elseif(isset($_POST['css_data']) && strlen($_POST['css_data']) > 0) {
		// Javascript disabled, take contens from textarea: css_data
		$css_content = stripslashes($_POST['css_data']);
	}

	$bytes = 0;
	if ($css_content != '') {
		// open the module CSS file for writting
		$mod_file = @fopen(dirname(__FILE__) .'/' .$_POST['edit_file'], "wb");
		// write new content to the module CSS file
		$bytes = @fwrite($mod_file, $css_content);
		// close the file
		@fclose($mod_file);
	}

	// write out status message
	if($bytes == 0 ) {
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}


} else {
	/** 
		MODIFY CONTENTS OF THE CSS FILE VIA TEXT AREA 
	*/
	
	// check which module file to edit (frontend.css, backend.css or '')
	$css_file = '';
	if(isset($_GET['edit_file'])) $css_file = edit_mod_file($_GET['edit_file']);

	// display output
	if($css_file == '') {
		// no valid module file to edit; display error message and backlink to modify.php
		echo "<h2>Nothing to edit</h2>";
		echo "<p>No valid module file exists for this module.</p>";
		$output  = "<a href=\"#\" onclick=\"javascript: window.location = '";
		$output .= ADMIN_URL ."/pages/modify.php?page_id=" .$page_id ."'\">back</a>";
		echo $output;
	} else {
		// store content of the module file in variable
		$css_content = @file_get_contents(dirname(__FILE__) .'/' .$css_file);
	  	// output content of module file to textareas

		// make sure that codepress stuff is only used if the framework is available
		$CODEPRESS['CLASS'] = '';
		$CODEPRESS['JS'] = '';
		if(file_exists(WB_PATH .'/include/codepress/codepress.js')) {
			$CODEPRESS['CLASS'] = 'class="codepress css" ';
			$CODEPRESS['JS'] = 'onclick="javascript: css_codepress.value = area_codepress.getCode();"';
		}
			
	?>
		<form name="edit_module_file" action="<?php echo $_SERVER['PHP_SELF'] .'?action=save';?>" method="post" style="margin: 0;">
	  		<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	  		<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
			<input type="hidden" name="css_codepress" value="" />
			<input type="hidden" name="edit_file" value="<?php echo $css_file; ?>" />
	
			<h2><?php echo $HEADING_CSS_FILE .'"' .$css_file; ?>"</h2>
			<?php 
				// include the toggle button to switch between frontend.css and backend.css (if both files exists)
				toggle_css_file($css_file); 
			?>
	  		<p><?php echo $TXT_EDIT_CSS_FILE; ?></p> 
			<textarea id="area_codepress" name="css_data" <?php echo $CODEPRESS['CLASS'];?>cols="115" rows="25" wrap="VIRTUAL" 
				style="margin:2px;"><?php echo $css_content; ?></textarea>

  			<table cellpadding="0" cellspacing="0" border="0" width="100%">
  			<tr>
    			<td align="left">
 				<input name="save" type="submit" value="<?php echo $TEXT['SAVE'];?>"
				  <?php echo $CODEPRESS['JS'];?> style="width: 100px; margin-top: 5px;" />
    			</td>
  				<td align="right">
      			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>"
						onclick="javascript: window.location = '<?php echo ADMIN_URL;?>/pages/modify.php?page_id=<?php echo $page_id; ?>';"
						style="width: 100px; margin-top: 5px;" />
  				</td>
  			</tr>
  			</table>
		</form>
		<?php 
	}
}

// Print admin footer
$admin->print_footer();

?>