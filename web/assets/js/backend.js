var Upload = (function ($) {
	dummy: function () {
		return (new Date()).getTime();
	},
	init: function (options, context) {
		var reader = new FileReader();
				reader.onload = function(event) {
					$.ajax({
						url: settings.service + '?' + $.param({
							dir: options.dir,
							thumb_dir: options.thumb_dir,
							thumbnail: options.thumbnail,
							name: (function (file) {
								var match = file.name.match(new RegExp("(.*)\\.(" + settings.allow.join('|') + ")$", "i"));
								var name = match[1];
								var extension = match[2];
								
								return options.pattern(name, extension, timestamp);
							})(file),
							type: file.type
						}),
						mimeType: 'text/plain; charset=x-user-defined-binary',
						xhr: function () {
							var xhr = $.ajaxSettings.xhr();							
							xhr.upload.addEventListener('progress', function (event) {
								if (settings.showProgress) {
									var per = Math.min(100, Math.round((event.loaded * 100) / event.total));									
									$bar.find('.fileupload-label').text(per + '% (' + Math.floor(event.loaded/1000) + 'K/' + Math.floor(event.total/1000) + 'K)');
									$bar.progressbar({
										value: per
									});
								}
								settings.onProgress(event);
							});
							return xhr;
						},						
						beforeSend: function (xhr) {
							$bar.find('.fileupload-label').text('0%');
							$bar.show();
						},						
						data: (function (datastr) {
							function byteValue(x) {
								return x.charCodeAt(0) & 0xff;
							}
							var ords = Array.prototype.map.call(datastr, byteValue);
							return new Uint8Array(ords);
						})(event.target.result),
						
						cache: false,
						contentType: false,
						processData: false,
						
						complete: function (jqXHR, textStatus) {
							if (settings.showProgress) {
								$bar.hide();
								$bar.find('.fileupload-label').text('0%');
							}
							
							if (current + 1 < files.length) {
								doUpload(files, ++current);
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							settings.onError(jqXHR, textStatus);
						},
						success: function (data, textStatus, jqXHR) {
							settings.onLoad(data, textStatus, jqXHR);
						},
						dataType: 'json',
						type: 'post'
					});
				};
				reader.readAsBinaryString(file);	
		
		options.extensions = options.extensions || ['gif', 'jpe?g', 'png'];
	
	
	
		$('#fileupload').fileupload('option', {
			url: options.url,
			dataType: 'json',
			acceptFileTypes: new RegExp('(\.|\/)(' + options.extensions.join('|') + ')$', 'i'),
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .bar').css('width', progress + '%');
			},
			process: [{
				action: 'load',
				fileTypes: new RegExp('^image\/(' + options.extensions.join('|').replace(/\?/, '') + ')$'),
				maxFileSize: 20000000 // 20MB
			},{
				action: 'resize',
				maxWidth: options.maxWidth,
				maxHeight: options.maxHeight
			},{
				action: 'save'
			}],
			done: function (e, data) {
				$.each(data.result.files, function (index, file) {
						$(context).val(file.name);
				});
			}
		}).fail(function () {
			$('<span class="alert alert-error"/>')
				.text('Upload server currently unavailable - ' + new Date())
				.appendTo('#fileupload');
		});
})(jQuery);