var Upload = (function ($) {
	init: function (options, context) {
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