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
    	  Upload.form.submit();  
      }
    },
    
    error: function (jqHXR, textStatus) {
      
    },
    
    load: function (data, textStatus, jqXHR) {
      $(Upload.context).val(data.name);
      
      Upload.options.end(data);
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
    
    init: function (options, context) {
      Upload.options = options;
      Upload.context = context;
    
      $('.fileupload').fileupload();
    
      $('#submit-btn').bind('click', function (event) {
        if ($('.fileupload :file').val()) {
          event.preventDefault();
          
          if ('form' in this && this.form) {
        	  Upload.form = this.form;
          }
          
          var file = $('.fileupload :file').get(0).files[0];          
          var extensions = Upload.options.allow.join('|');
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
          }
        } else {
          this.form.submit();
        }
      });
    }
  }
})(jQuery);