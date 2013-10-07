// ====================================
// Javascript for displaying the Editor
// ------------------------------------

// Offsets of edit box from anchor link
var horizontal_offset="10px";
var vertical_offset="15px";

// Compatibility checks:
var ie=document.all
var ns6=document.getElementById&&!document.all
function ie_compatibility_test(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

//
//
// =========
// Functions
// ---------

// Define the getElementsByClassName function:
document.getElementsByClassName = function(cl) {
	var retnode = [];
	var myclass = new RegExp('\\b'+cl+'\\b');
	var elem = this.getElementsByTagName('*');
	for (var i = 0; i < elem.length; i++) {
		var classes = elem[i].className;
		if (myclass.test(classes)) retnode.push(elem[i]);
	}
	return retnode;
}
//
// Display Logic Functions
function get_position_Offset (what, offsettype){
	var totaloffset = (offsettype=="left") ? what.offsetLeft : what.offsetTop;

	var parentElement = what.offsetParent;
	while (parentElement != null){
		totaloffset = (offsettype=="left") ? totaloffset + parentElement.offsetLeft : totaloffset + parentElement.offsetTop;
		parentElement = parentElement.offsetParent;
	}

	return totaloffset;
}

function clear_browser_edge (obj, editor_form, whichedge){

	var edgeoffset = (whichedge=="rightedge") ? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1;
	if (whichedge=="rightedge"){
		var windowedge = ie && !window.opera ? ie_compatibility_test().scrollLeft+ie_compatibility_test().clientWidth-30 : window.pageXOffset+window.innerWidth-40;
		editor_form.contentmeasure = editor_form.offsetWidth;
		if (windowedge - editor_form.x < editor_form.contentmeasure) {  // the editor box goes off the right edge of the screen
			edgeoffset = editor_form.contentmeasure - (windowedge - editor_form.x);
		}
	}
	else {
		var windowedge=ie && !window.opera? ie_compatibility_test().scrollTop+ie_compatibility_test().clientHeight-15 : window.pageYOffset+window.innerHeight-18
		editor_form.contentmeasure=editor_form.offsetHeight;
		if (windowedge - editor_form.y < editor_form.contentmeasure) { // The editorbox goes off the bottom edge of the screen.
			edgeoffset = editor_form.contentmeasure - (windowedge - editor_form.y);
		}
	}
	return edgeoffset;
}
//
// Edit Box Functions
function show_edit (phrase_key, phrase_content, text_box_size, text_box_height, obj, e, tipwidth){
	if ((ie||ns6) && document.getElementById("editor_box")){
		var editor_form = document.getElementById("editor_box");

		if (text_box_height > 0) { // This is supposed to be a textarea element
			var phrase_child = document.getElementById("phrase");
			if (phrase_child.type == 'text') {
				// Create new textarea element
				var new_textarea = document.createElement("textarea");
				new_textarea.setAttribute('id','phrase');
				new_textarea.setAttribute('class','edit_textbox');
				new_textarea.setAttribute('name','phrase');
				new_textarea.setAttribute('wrap','soft');
				new_textarea.setAttribute('rows',text_box_height);
				new_textarea.setAttribute('cols',text_box_size);
				// replace the text element with the new textarea one.
				document.editor_form.replaceChild(new_textarea ,phrase_child);
				document.editor_form.phrase.style.marginBottom = '-5px';
			}
			else { // Already is a textarea. resize:
				document.editor_form.phrase.cols = text_box_size;
				document.editor_form.phrase.rows = text_box_height;
			}

		}
		else { // Assume this is a regular text element
			var phrase_child = document.getElementById("phrase");
			if (phrase_child.type == 'textarea') {
				// Create new text element
				var new_textbox = document.createElement("input");
				new_textbox.setAttribute('type','text');
				new_textbox.setAttribute('id','phrase');
				new_textbox.setAttribute('class','edit_textbox');
				new_textbox.setAttribute('name','phrase');
				new_textbox.setAttribute('size',text_box_size);
				new_textbox.setAttribute('maxlength','255');
				new_textbox.setAttribute('align','bottom');
				// replace the textarea element with the new textbox one.
				document.editor_form.replaceChild(new_textbox ,phrase_child);
			}
			else { // Already is a text box. resize:
				document.editor_form.phrase.size = text_box_size;
			}
		}

		//
		// Assign the proper values:
		document.editor_form.phrase.value = phrase_content;
		document.editor_form.phrase_key.value = phrase_key;
		document.editor_form.element_name.value = phrase_key;

		//editor_form.style.left = editor_form.style.top=-500;
		if (tipwidth!=""){
			editor_form.widthobj = editor_form.style;
			editor_form.widthobj.width = tipwidth;
		}
		editor_form.x = get_position_Offset(obj, "left");
		editor_form.y = get_position_Offset(obj, "top");
		editor_form.style.left = editor_form.x - clear_browser_edge(obj, editor_form, "rightedge")+"px";
		editor_form.style.top = editor_form.y - clear_browser_edge(obj, editor_form, "bottomedge")+"px";
		editor_form.style.visibility="visible";
		//editor_form.onmouseout=hide_edit;
	}
}

function hide_edit (){
	document.getElementById("editor_box").style.visibility="hidden";
	document.getElementById("editor_box").style.left="-500px";
}




//
// =========
//   Ajax
// ---------

// Submit function:
function submit_phrase_change() {
   var phrase = document.getElementById("phrase").value;
	var phrase_user = document.getElementById("phrase_u").value;
	var phrase_pswd = document.getElementById("phrase_p").value;
	var phrase_def = document.getElementById("phrase_key").value;
	var element_name = document.getElementById("element_name").value;
   var url = "index.php";
	var params = "action=phrase_change&admin_name="+phrase_user+"&admin_password="+phrase_pswd+"&phrase_key="+phrase_def+"&phrase="+escape(phrase);

	api_request.open("POST", url, true);
	api_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	api_request.setRequestHeader("Content-length", params.length);
	api_request.setRequestHeader("Connection", "close");
	api_request.onreadystatechange = function() {
		if (api_request.readyState == 4) {

			var xmlDoc = api_request.responseXML;
			var response_status = xmlDoc.getElementsByTagName("response")

			if (response_status[0].getAttribute("status") == "success") {
				//document.getElementById(element_name).innerHTML = phrase;
				var all_same_elements = document.getElementsByClassName(element_name);
				for ( var each_element in all_same_elements ) {
					all_same_elements[each_element].innerHTML = phrase;
				}
				hide_edit();

			} else {
				alert(response_status[0].getAttribute("status"));
			}
		}
	};
	api_request.send(params);
}

//
//
var api_request = false;
try {
  api_request = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    api_request = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      api_request = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      api_request = false;
    }
  }
}
if (!api_request) {
  alert("Error initializing XMLHttpRequest!");
}
