<?php
function convert_member_link ($m_link, $hide_email) {

		
	$the_array = explode(" ", $m_link);
			
	$word_1 = $the_array[0];	
	$this_is = 0;
	if (substr($word_1, 0, 4) == "http") {
		$this_is = 1;
	} else {
		if (strpos($word_1, '@') > 0 AND strpos($word_1, '.' ) > 0) {$this_is = 2;}	
	}
	
	if ($this_is == 0) { return $m_link; }
	
	$linktext = '';	
	if (count($the_array) > 1) {
		unset($the_array[0]);
		$linktext = trim(implode(' ', $the_array));		
	}
		
	//Link:
	if ($this_is == 1) {
		if ($linktext == '') {$linktext = 'Link';} 
		return ('<a href="'.$word_1.'" target="_blank">'.$linktext.'</a>'); 
	}
				
	//Mail:
	
	if ($hide_email == 1) { 
		$word_1 = str_replace("@", "','", $word_1);
		$m_link = "<script type=\"text/javascript\">showmembermail('".$word_1."','".$linktext."');</script>"; 
	} else {
		if ($linktext == '') {$linktext = $word_1;} 
		$m_link = '<a href="mailto:'.$word_1.'">'.$linktext.'</a>'; 
	}
	return $m_link;
	
}
?>