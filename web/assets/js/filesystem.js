var FileSystem = (function () {
	return {
		dialog: function (files) {
			var sb = $(files).map(function (index, file) {
				return '<a href="' + file + '" target="_blank">' + 
					'<img src="' + file.replace(DIR, THUMB_DIR) + '">' + 
				'</a>';
			}).toArray();
			
			$('.dialog-filelist').html(sb.join('\n'));
			
			$('#dialog').modal('show');
		},			
		init: function () {
			$('#error-refresh').click(function (event) {
				event.preventDefault();
				
				window.location.reload(true);				
			});
			
			$('#dialog-confirm').click(function (event) {
				event.preventDefault();
				
				$('#dialog').modal('hide');
				
				var files = [];
				
				$('.dialog-filelist a').each(function (index, anchor) {
					files.push(anchor.pathname.replace(DIR, THUMB_DIR));
					files.push(anchor.pathname);
				});
				
				
				
				FileSystem.rm(files, function (data) {
					$('.dialog-filelist').empty();
					
					if (data) {
						$(data).each(function (index, file) {
							$('tr[data-path="' + file + '"]').remove();
						});
					} else {
						$("#error").modal('show');
					}
				});
			});
			
			$('#dialog-cancel').click(function (event) {
				event.preventDefault();
				
				$('.dialog-filelist').empty();
				
				$('#dialog').modal('hide');
			});
			
			$('#all').click(function (event) {
				if ($(this).is(':checked')) {
					$(':checkbox:not(#all)').attr('checked', true);
				} else {
					$(':checkbox').attr('checked', false);
				}
			});
			
			$('.btn-checked').click(function (event) {
				event.preventDefault();
				
				var files = $(':checked:not(#all)').map(function (index, checkbox) {
					return $(checkbox).val();
				}).toArray();
				
				FileSystem.dialog(files);
			});
			
			$('.btn-remove').click(function (event) {
				event.preventDefault();
				
				FileSystem.dialog([this.pathname]);
			});
		},
		rm: function (files, fn) {
			$.post('/filesystem/remove', { files: files }, fn, 'json');
		}
	};	
})(jQuery);