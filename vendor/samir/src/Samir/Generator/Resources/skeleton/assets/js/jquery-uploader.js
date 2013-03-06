(function ($) {
	$.fn.uploader = function (options) {
		var events = {
			load: function () {
				
			},
			progress: function (event) {
				
			},
			error: function () {
				
			},			
			unlink: function () {
			
			}
		};
	
		var settings = $.extend({
			'dir':					'/uploads',
			'thumbDir':			'/uploads/thumbs',
      'service':			'/path/to/service',
			'unlink':				'/unlink',
			'multiple':			false,
			'onDelete':			events.unlink,
			'onLoad':				events.load,
			'onProgress':		events.progress,
			'onError':			events.error,
			'dragDrop':			false,
			'type':					'bootstrap:file',
			'showProgress':	true,
			'showPreview':  true,
			'thumbnail':		true,
			'pattern':			function (name, extension) {
				return name + '.' + extension;
			},
			'allow':				[ '.*' ],
			'trans':				{
				'Change':				'Change',
				'Delete':				'Delete',
				'Remove':				'Remove',
				'Select file':	'Select file'
			}
    }, options);
		
		function allowed(filename) {
			var extensions = settings.allow.join('|');
			var regexp = new RegExp('(.*)\\.(' + extensions + ')$', 'i');
			return regexp.test(filename);
		}
		
		var current = 0;
		
		function dummy() {
			return (new Date()).getTime();
		}
	
		var timestamp;
		
		function upload(files) {
			if (files) {
				timestamp = dummy();
			
				doUpload.call(this, files, current);
			}
		}
		
		function doUpload(files, current) {
			var that = this;
			var $bar = $('.fileupload-progress', that).progressbar({
				value: 0
			})
			.hide();
			
			var file = files[current];
			
			if (allowed(file.name)) {	
				var reader = new FileReader();
				reader.onload = function(event) {
					$.ajax({
						url: settings.service + '?' + $.param({
							dir: settings.dir,
							thumb_dir: settings.thumbDir,
							thumbnail: settings.thumbnail,
							name: (function (file) {
								var match = file.name.match(new RegExp("(.*)\\.(" + settings.allow.join('|') + ")$", "i"));
								var name = match[1];
								var extension = match[2];
								
								return settings.pattern(name, extension, timestamp);
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
			} else {
				settings.onError({ }, "Format not allowed.");
			}
		}
		
		return this.each(function () {
			var that = this;
			
			if (settings.dragDrop || settings.type == 'dragdrog') {
				$(this).on("dragover drop", function(event) {
					e.preventDefault();
				}).on("drop", function(event) {
					var files = event.originalEvent.dataTransfer.files;
					upload.call(that, files);
				});
			} else {
				if ($(this).is(':file') || settings.type == 'input') {
					$(this).change(function(event) {
						upload.call(that, this.files);
					});
				} else {
					var html;					
					var bar = '<div class="fileupload-progress">' + 
						'<div class="fileupload-label"></div>'
					'</div>';
					
					switch (settings.type) {
						case 'bootstrap:widget' :
							// TODO
							break;
							
						case 'bootstrap:button' :
							html = '<div class="fileupload fileupload-new" data-provides="fileupload">' +
									'<span class="btn btn-file">' + 
										'<span class="fileupload-new">' + settings.trans['Select file'] + '</span>' + 
										'<span class="fileupload-exists">' + settings.trans['Change'] + '</span>' + 
										'<input type="file" ' + (settings.multiple ? 'multiple' : '') + '>' + 
									'</span>'+								
									'<span class="fileupload-preview"></span>' +
									'<a href="#" class="close fileupload-exists fileupload-remove" data-dismiss="fileupload" style="float: none">×</a>' +
									(settings.showProgress ? bar : '') +
								'</div>';
							break
							
						case 'bootstrap:file' :
							html = '<div class="fileupload fileupload-new" data-provides="fileupload">' +
								'<div class="input-append">' +
									'<div class="uneditable-input span3">' +
										'<i class="icon-file fileupload-exists"></i>' +
										'<span class="fileupload-preview"></span>' +
									'</div>' +
									'<span class="btn btn-file">' +
										'<span class="fileupload-new">' + settings.trans['Select file'] + '</span>' +
										'<span class="fileupload-exists">' + settings.trans['Change'] + '</span>' +
										'<input type="file" ' + (settings.multiple ? 'multiple' : '') + '>' +
									'</span>' +
									'<a href="#" class="btn fileupload-exists fileupload-remove" data-dismiss="fileupload">' + settings.trans['Remove'] + '</a>' +									
								'</div>' +
								(settings.showProgress ? bar : '') +
							'</div>';
							break
						
						default :
							break
					}
					
					$(html).appendTo(this)
						.fileupload({
							uploadtype: 'file',
							name: 'file'
						})
						.find(':file')
						.change(function(event) {
							upload.call(that, this.files);
						})
						.end()
						.find('.fileupload-remove')
						.on('click', function (event) {
							event.preventDefault();
							// TODO remove
							$.post(settings.unlink, { 
								file: $(this).attr('data-src') 
							}, function (data) {
								if (data) {
									settings.onDelete();
								}
							}, 'json');
						});;
				}
			}
		});
	};
})(jQuery);

$(function () {
	$('.checkbox a').on('click', function (event) {
		event.preventDefault();		
		$(this).parents('.checkbox')
			.find(':text').val($(this).attr('data-value'));
	})
});