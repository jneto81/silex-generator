var EmbeddedUpload = (function ($) {
  return function (submiter) {
    var self = this;
    
    this.$submiter = $(submiter);
  
    this.complete = function () {
      if (this.$submiter.get(0).form.checkValidity()) {
        this.$submiter.get(0).form.submit();  
      }
    }
    
    this.pointer = 0;
    this.count = 0;
    
    this.timestamp = function () {
      return (new Date()).getTime();
    }
    
    this.init = function (context) {
      this.context = context;
      
      this.$submiter.on('click', function (event) {
        event.preventDefault();
      
        var $inputs = $(self.context);
        
        self.count = $inputs.size();
      
        $inputs.each(function (index, input) {
          var errors = [];
          var $fileInput = $('.fileupload :file', input);
          
          if ($fileInput.val()) {
            var file = $fileInput.get(0).files[0];
            var extensions = $('.fileupload [role="file"]', input).attr('allow');
            var allow = new RegExp("(.*)\\.(" + extensions + ")$", "i");
            var matches;
            
            if (matches = file.name.match(allow)) {
              var name = self.timestamp() + '.' + matches[2]; /* extension */
              
              var fileItem = new FileItem(file, name);
              fileItem.end = function (name) {
                self.pointer++;
                
                if (self.pointer >= self.count) {
                  self.pointer = 0;
                  self.count = 0;
                  self.complete();
                }
              }
              fileItem.init(input);
            } else {
              errors.push(file.name);
            }
          } else {
            self.count--;
          }
          
          if (errors.length) {
            $('#upload-error').show();
            
            setTimeout(function () {
              $('#upload-error').hide();
            }, 5000);
          };
        });
        
        if ( ! self.count) {
          self.complete();
        }
      });
    }
  }
})(jQuery);

var FileItem = (function ($) {
  return function (file, name) {
    var self = this;
  
    this.name = name;
    this.file = file;
    
    this.init = function (context) {
      this.$context = $('.fileupload [role="file"]', context);
      this.$preview = $('.fileupload .fileupload-thumb', context);
      
      this.options = {
        url: this.$context.attr('url'),
        dir: this.$context.attr('dir'),
        showProgress: Boolean(parseInt(this.$context.attr('data-show-progress'))),
        showPreview: Boolean(parseInt(this.$context.attr('data-show-preview'))),
        thumbnail: Boolean(parseInt(this.$context.attr('data-thumbnail')))
      };
      
      var url = this.options.url + '?' + $.param({
        type: this.file.type,
        thumbnail: this.options.thumbnail,
        name: this.name,
        dir: this.options.dir
      });
      
      var reader = $(new FileReader());
      reader.load(function (event) {        
        $.ajax({
          url: url,
          cache: false,
          contentType: false,
          processData: false,
          data: self.data(event.target.result),
          mimeType: 'text/plain; charset=x-user-defined-binary',
          dataType: 'json',
          type: 'post',
          xhr: function () {
            return self.xhr();							
          },
          beforeSend: function (xhr) {
            self.show();
          },	
          complete: function (jqXHR, textStatus) {
            self.complete();
          },
          error: function (jqXHR, textStatus, errorThrown) {
            self.error(jqXHR, textStatus);
          },
          success: function (data, textStatus, jqXHR) {
            self.load(data, textStatus, jqXHR);
          },
        });
      });
      reader.get(0).readAsBinaryString(file);
    }
    
    this.show = function () {
      $('.progression', this.$context).find('.bar')
        .text('0%')
        .css('width', '0%')
        .show();
    }
    
    this.hide = function () {    
      $('.progression', this.$context).hide()
        .find('.bar')
        .text('0%')
        .css('width', '0%');
    }
    
    this.complete = function () {
      this.hide();
    }
    
    this.error = function (jqHXR, textStatus) {
      
    }
    
    this.load = function (data, textStatus, jqXHR) {
      $(this.$context).val(data.name);
      
      this.$preview.attr('src', '/uploads/thumbs/' + data.name);      
      
      this.end(data.name);
    }
    
    this.progress = function (event) {    
      var per = Math.min(100, Math.round((event.loaded * 100) / event.total));
      
      $('.progression', this.$context).find('.bar')
        .text(per + '% (' + Math.floor(event.loaded/1000) + 'K/' + Math.floor(event.total/1000) + 'K)')
        .css('width', per + '%');
    }
    
    this.xhr = function () {
      var xhr = $.ajaxSettings.xhr();
      xhr.upload.addEventListener('progress', function (event) {
        self.progress(event);
      });
      return xhr;
    },
    
    this.data = function (data) {
      return new Uint8Array(Array.prototype.map.call(data, function byteValue (x) {
        return x.charCodeAt(0) & 0xff;
      }));
    }
    
  }
})(jQuery);