<?php
// this file contains the contents of the popup window
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>INSERT TIMELINR</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<style type="text/css" src="../../../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css"></style>
<link rel="stylesheet" href="../assets/css/shortcode_popup.css" />

<script type="text/javascript">
 
var timelinrDialog = {
	local_ed : 'ed',
	init : function(ed) {
		timelinrDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertTimelinr(ed) {
 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
 
		// set up variables to contain our input values
		var orientation = jQuery('#button-dialog select#button-orientation').val();
		var arrowkeys = jQuery('#button-dialog select#button-arrowkeys').val();
		var autoplay = jQuery('#button-dialog select#button-autoplay').val();
		var category = jQuery('#button-dialog input#button-category').val();
		var order = jQuery('#button-dialog select#button-order').val(); 
		var dateformat = jQuery('#button-dialog select#button-dateformat').val(); 
 
		var output = '';
 
		// setup the output of our shortcode
		output = '[timelinr';
		
		// only insert if the orientation field is not blank
		if(orientation)
			output += ' orientation="' + orientation + '"';
		// only insert if the arrowkeys field is not blank
		if(arrowkeys)
			output += ' arrowkeys="' + arrowkeys + '"';
		// only insert if the autoplay field is not blank
		if(autoplay)
			output += ' autoplay="' + autoplay + '"';
		// only insert if the category field is not blank
		if(category)
			output += ' category="' + category + '"';
		// only insert if the order field is not blank
		if(order)
			output += ' order="' + order + '"';
		
		if(dateformat)
			output += ' dateformat=' + dateformat;

		output += ']';


		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(timelinrDialog.init, timelinrDialog);
 
</script>

<div id="button-dialog">
	<form action="/" method="get" id="timelinr-popup" accept-charset="utf-8">
		<div>
			<label for="button-orientation">Orientation</label>
			<select name="button-orientation" id="button-orientation" size="1">
				<option value="" selected="selected"></option>
				<option value="horizontal">Horizontal</option>
				<option value="vertical">Vertical</option>
			</select>
		</div>
		<div>
			<label for="button-category">Category</label>
			<input type="text" name="button-category" value="" id="button-category" />
		</div>
		<div>
			<label for="button-arrowkeys">Arrowkeys</label>
			<select name="button-arrowkeys" id="button-arrowkeys" size="1">
				<option value="" selected="selected"></option>
				<option value="false">False</option>
				<option value="true">True</option>
			</select>
		</div>
		<div>
			<label for="button-autoplay">Autoplay</label>
			<select name="button-autoplay" id="button-autoplay" size="1">
				<option value="" selected="selected"></option>
				<option value="false">False</option>
				<option value="true">True</option>
			</select>
		</div>
		<div>
			<label for="button-order">Order</label>
			<select name="button-order" id="button-order" size="1">
				<option value="" selected="selected"></option>
				<option value="desc">Desc</option>
				<option value="asc">Asc</option>
			</select>
		</div>
		<div>
			<label for="button-dateformat">Date format</label>
			<select name="button-dateformat" id="button-dateformat" size="1">
				<option value="" selected="selected"></option>
				<option value="yy">Year</option>
				<option value="yy/mm">Year/Month</option>
				<option value="mm/yy">Month/Year</option>
			</select>
		</div>
		<div>	
			<a href="javascript:timelinrDialog.insert(timelinrDialog.local_ed)" id="insert" class="small right round blue">Insert</a>
		</div>
	</form>
</div>