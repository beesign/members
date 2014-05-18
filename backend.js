function changepic(byinput) {
	
	if (document.modify.m_picture.value) {
		var bildname = document.modify.m_picture.value;
	} else {
		var bildname = document.modify.m_picture.options[document.modify.m_picture.selectedIndex].value;
	}
	
	
	o = 6 + bildname.indexOf(".gif") +  bildname.indexOf(".jpg")  +  bildname.indexOf(".png")+ bildname.indexOf(".GIF") +  bildname.indexOf(".JPG")  +  bildname.indexOf(".PNG");
	
	if (o > 0) {
		if(bildname.substr(0,7) != "http://") {bildname = memberpicloc + bildname;}
		document.images['memberpic'].src = bildname;
		document.images['memberpic'].style.display = "block";
	} else {
		document.images['memberpic'].style.display = "none";
	}
}

function makevisible(what) {
	document.getElementById(what).style.display="block";
	if (what != 'getfromtable' && document.getElementById("getfromtable")) document.getElementById("getfromtable").style.display="none";
	if (what != 'presetstable' && document.getElementById("presetstable")) document.getElementById("presetstable").style.display="none";
	
	
}


function changesettings(sid) {

	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}
	
	var script = document.createElement( 'script' );
	if ( script ) {
    	script.setAttribute( 'type', 'text/javascript' );
    	script.setAttribute( 'src', theurl + sid);
 		//alert(theurl + sid);
	
    	var head = document.getElementsByTagName( 'head' )[ 0 ];
    	if ( head ) {
     		head.appendChild( script );
    	}
   	}

}

function changepresets(thefile) {
	
	if (!thelanguage) {thelanguage = "en";}
	
	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}	
	if (script) { head.Child( script ).setAttribute( 'src', 'presets-'+thelanguage+'/'+thefile+'.js' ); }
	else {
 	var script = document.createElement( 'script' );
	if ( script ) {
    	script.setAttribute( 'type', 'text/javascript' );
    	script.setAttribute( 'src', 'presets-'+thelanguage+'/'+thefile+'.js' );
 
	
    	var head = document.getElementsByTagName( 'head' )[ 0 ];
    	if ( head ) {
     		head.appendChild( script );
    	}
   	}
	}
}


function selectDropdownOption(element,wert) {
	for (var i=0; i<element.options.length; i++) 
	{
		if (element.options[i].value == wert) 
		{
			element.options[i].selected = true;		
		} else	{
			element.options[i].selected = false;	
		}
	}
}


//Ajax Script based on http://www.degraeve.com/reference/simple-ajax-example.php

function xmlhttpPostLink(strURL) {
    var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            updatememberpage(self.xmlHttpReq.responseText);
        }
    }
    self.xmlHttpReq.send(getmemberpagelink());
}

function getmemberpagelink() {
    var form     = document.forms['modify'];
    var m_memberpage_id = form.m_memberpage_id.value;	
    qstr = 'memp=' + escape(m_memberpage_id);  // NOTE: no '?' before querystring
    return qstr;
}

function updatememberpage(str){
    document.getElementById("memberpagelink").innerHTML = str;
}



//-----------------------------------------------------------------------------------
function openpicturepreviews() {
	
	document.getElementById('picturechooser').style.display = "block";
	document.getElementById('picturechooser').innerHTML = "<h2>wird geladen</h2>";
	
	getpicturepreviews()
	
}


function getpicturepreviews() {
    var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
	
	strURL = "modify_member.pictures.php";
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            showpicturepreviews(self.xmlHttpReq.responseText);
        }
    }
    self.xmlHttpReq.send(getresults());
}

function getresults() {   
	qstr = 's=' + escape(membersection); // NOTE: no '?' before querystring
	//alert(qstr);
    return qstr;
}

function showpicturepreviews(str){
    //document.getElementById("suggestbox").innerHTML = '<div class="ajax">'+str+'</div>';
	document.getElementById('picturechooser').innerHTML = str;
	document.getElementById('picturechooser').style.display = "block";
}


function choosethispicture(picfile) {
	//alert (picfile);
	
	document.images['memberpic'].style.display = "block";
	document.getElementById('picturechooser').style.display = "none";
	if (picfile!=0) {
		document.images['memberpic'].src = memberpicloc + picfile;	
		document.getElementById('m_picture').value=picfile;
	}
}
