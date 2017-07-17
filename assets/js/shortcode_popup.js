(function() {
	tinymce.create('tinymce.plugins.timelinr', {
		init : function(ed, url) {
			url = url.substring(0, url.lastIndexOf('/assets/'));
			
			// Register commands
			ed.addCommand('mcetimelinr', function() {
				ed.windowManager.open({
					file : url + '/includes/shortcode_popup.php', // file that contains HTML for our modal window
					width : 300 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 240 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
 
			// Register buttons
			ed.addButton('timelinr', {
				title : 'Insert Timelinr',
				cmd : 'mcetimelinr', 
				image: url + 'assets/images/clock-icon.png'
			});
		},
 
		getInfo : function() {
			return {
				longname : "Timelinr",
            	author : 'Broobe',
            	authorurl : 'http://www.broobe.com',
            	infourl : 'http://www.broobe.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('timelinr', tinymce.plugins.timelinr);
})();