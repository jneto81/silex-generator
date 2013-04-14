var Upload = (function ($) {
  return {
    timestamp: function () {
      return (new Date()).getTime();
    },
    
    show: function () {
      $('#progress').find('.bar')
        .text('0%')
        .css('width', '0%')
        .show();
    },
    
    hide: function () {    
      $('#progress').hide()
        .find('.bar')
        .text('0%')
        .css('width', '0%');
    },
    
    complete: function () {
      if (Upload.options.showProgress) {
        Upload.hide();
      }
      
      if ('form' in Upload) {
        if (Upload.form.checkValidity()) {
          Upload.form.submit();  
        }
      }
    },
    
    error: function (jqHXR, textStatus) {
      
    },
    
    load: function (data, textStatus, jqXHR) {
      Upload.$context.val(data.name);
      
      if (Upload.showPreview) {
        Upload.$preview.attr('src', '/uploads/thumbs/' + data.name);
      }
    },
    
    progress: function (event) {    
      var per = Math.min(100, Math.round((event.loaded * 100) / event.total));
      
      $('#progress').find('.bar')
        .text(per + '% (' + Math.floor(event.loaded/1000) + 'K/' + Math.floor(event.total/1000) + 'K)')
        .css('width', per + '%');
    },
    
    xhr: function () {
      var xhr = $.ajaxSettings.xhr();
      xhr.upload.addEventListener('progress', function (event) {
        if (Upload.options.showProgress) {
          Upload.progress(event);
        }
      });
      return xhr;
    },
    
    data: function (data) {
      return new Uint8Array(Array.prototype.map.call(data, function byteValue (x) {
        return x.charCodeAt(0) & 0xff;
      }));
    },
    
    init: function (context) {
      var $input = $('.fileupload :file', context.form);
      var $context = $('.fileupload [role="file"]', context.form);
      
      Upload.$preview = $('.fileupload .fileupload-thumb', context.form);
      Upload.$context = $context;
      Upload.options = {
        url: $context.attr('url'),
        dir: $context.attr('dir'),
        allow: $context.attr('allow'),
        showProgress: Boolean(parseInt($context.attr('data-show-progress'))),
        showPreview: Boolean(parseInt($context.attr('data-show-preview'))),
        thumbnail: Boolean(parseInt($context.attr('data-thumbnail')))
      };
      
      $('.fileupload').fileupload();
      
      $(context).bind('click', function (event) {
        if ($input.val()) {
          event.preventDefault();
          
          if ('form' in this && this.form) {
        	  Upload.form = this.form;
          }
          
          var file = $input.get(0).files[0];          
          //var extensions = Upload.options.allow.join('|');
          var extensions = Upload.options.allow;
          var allow = new RegExp("(.*)\\.(" + extensions + ")$", "i");
          
          if (allow.test(file.name)) {
            var matches = file.name.match(allow);
            var name = Upload.timestamp() + '.' + matches[2]; /* extension */
          
            var url = Upload.options.url + '?' + $.param({
              type: file.type,
              thumbnail: Upload.options.thumbnail,
              name: name,
              dir: Upload.options.dir
            });
          
            var reader = $(new FileReader());
            reader.load(function (event) {
              $.ajax({
                url: url,
                cache: false,
                contentType: false,
                processData: false,
                data: Upload.data(event.target.result),
                mimeType: 'text/plain; charset=x-user-defined-binary',
                dataType: 'json',
                type: 'post',
                xhr: function () {
                  return Upload.xhr();							
                },
                beforeSend: function (xhr) {
                  Upload.show();
                },	
                complete: function (jqXHR, textStatus) {
                  Upload.complete();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  Upload.error(jqXHR, textStatus);
                },
                success: function (data, textStatus, jqXHR) {
                  Upload.load(data, textStatus, jqXHR);
                },
              });
            });
            reader.get(0).readAsBinaryString(file);	
          } else {
            $('#upload-error').show();
            
            setTimeout(function () {
              $('#upload-error').hide();
            }, 5000);
          }
        } else {
          if (this.form.checkValidity()) {
            this.form.submit();
          }
        }
      });
    }
  }
})(jQuery);