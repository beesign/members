$(document).ready(function()
{
$.insert('../../include/jquery/jquery-ui-min.js');
});

$(document).ready(function(){ 
	$(function() { 
		$("#dragableTable ul").sortable({ opacity: 0.6, cursor: 'move', update: function() { 
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings&group='+the_group; 
			$.post(WB_URL+"/modules/members/kram/reorderDND.php", order, function(theResponse){ 
				$("#dragableResult").html(theResponse); 
			}); 
		} 
		}); 
	}); 
 }); 
$(document).ready(function(){ 
		$("#dragableTable a").mousedown(function() {			document.location = ($(this).attr("href") );
	}); 
 }); 
