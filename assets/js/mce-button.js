(function() {
	tinymce.PluginManager.add('swf', function( editor, url ) {
		var sh_tag = 'swf';

		//helper functions 
		function getAttr(s, n) {
			n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
			return n ?  window.decodeURIComponent(n[1]) : '';
		};

		//add popup
		editor.addCommand('swf_popup', function(ui, v) {
			//setup defaults
			var source = '';
			if (v.source)
				source = v.source;

			var height = '';
			if (v.height)
				height = v.height;
			
			var width = '';
			if (v.width)
				width = v.width;
			
			var type = 'default';
			if (v.type)
				type = v.type;
			var content = '';
			if (v.content)
				content = v.content;

			editor.windowManager.open( {
				title: 'Anywhere Flash Shortcode Generator.',
				body: [
					{
						type: 'textbox',
						name: 'source',
						label: 'Flash Source Link',
						value: width,
						tooltip: 'Enter Your Flash File Link Here.'
					},

					{
						type: 'textbox',
						name: 'height',
						label: 'Height',
						value: height,
						tooltip: 'Enter Height Here.'
					},
					{
						type: 'textbox',
						name: 'width',
						label: 'Width',
						value: width,
						tooltip: 'Enter File Width Here.'
					},
					{
						type: 'textbox',
						name: 'params',
						label: 'Enter Params.',
						value: content,
						multiline: true,
						minWidth: 300,
						minHeight: 100,
						tooltip: 'Enter Params Here.'
					},
					
					{
						type: 'textbox',
						name: 'flashvars',
						label: 'Flash Vars.',
						value: content,
						multiline: true,
						minWidth: 300,
						minHeight: 100,
						tooltip: 'Enter Flashvars Here.'
					}, 
					{
						type: 'textbox',
						name: 'content',
						label: 'Error Message.',
						value: content,
						tooltip: 'Error Message When Browser does not support Flash File.'
					}
				],
				onsubmit: function( e ) {
					var shortcode_str = '[' + sh_tag + ' ';
					
					//check for source
					if (typeof e.data.source != 'undefined' && e.data.source.length)
						shortcode_str += ' src="' + e.data.source + '"';

					//check for heigth
					if (typeof e.data.height != 'undefined' && e.data.height.length)
						shortcode_str += ' height="' + e.data.height + '"';
					
					//check for width
					if (typeof e.data.width != 'undefined' && e.data.width.length)
						shortcode_str += ' width="' + e.data.width + '"';

					//check for params
					if (typeof e.data.params != 'undefined' && e.data.params.length)
						shortcode_str += ' params="' + e.data.params + '"';

					//check for flashvars
					if (typeof e.data.flashvars != 'undefined' && e.data.flashvars.length)
						shortcode_str += ' flashvars="' + e.data.flashvars + '"';

					//add panel content
					shortcode_str += ']' + e.data.content + '[/' + sh_tag + ']';
					//insert shortcode to tinymce
					editor.insertContent( shortcode_str);
				}
			});
	      	});

		//add button
		editor.addButton('swf', {
			icon: 'swf',
			tooltip: 'Anywhere Flash Embed Shortcode Generator',
			onclick: function() {
				editor.execCommand('swf_popup','',{
					src : '',
					height : '',
					width : '',
					content: '',
					params: '',
					flashvars: ''

				});
			}
		});


		//open popup on placeholder double click
		editor.on('DblClick',function(e) {
			var cls  = e.target.className.indexOf('wp-swf');
			if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-swf') > -1 ) {
				var title = e.target.attributes['data-sh-attr'].value;
				title = window.decodeURIComponent(title);
				console.log(title);
				var content = e.target.attributes['data-sh-content'].value;
				editor.execCommand('swf_popup','',{
					height : getAttr(title,'height'),
					width : getAttr(title,'width'),
					source   : getAttr(title,'source'),
					params   : getAttr(title,'params'),
					flashvars   : getAttr(title,'flashvars'),
					content: content
				});
			}
		});
	});
})();