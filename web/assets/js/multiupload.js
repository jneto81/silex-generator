var MultiUpload = (function ($) {
  return function () {
    var self = this;
  
    this.complete = function () {
      $('#dropbox-save').click(function (event) {
        event.preventDefault();
        
        var media = [];
        
        if ($('#brand').val()) {
          $('#dropbox-list tbody tr').each(function (index, row) {
            media.push({
              title: $('.title', row).val(),
              caption: $('.caption', row).val(),
              src: $(row).attr('data-src')
            });
          });
        
          $.post('/media/upload', {
            brand: $('#brand').val(),
            media: media
          }, function (data) {
            if (data) {
              $('#modal-success').show();
              
              setTimeout(function () {
                $('#modal-success').hide();
              }, 3000);
            }
          }, 'json');
        } else {
          $('#modal-warning').show();
          
          setTimeout(function () {
            $('#modal-warning').hide();
          }, 3000);
        }
      }).show();
    }
    
    this.pointer = 0;
    this.count = 0;
    
    this.timestamp = function () {
      return (new Date()).getTime();
    }
    
    this.init = function (options, context) {
      this.options = options;
      this.context = context;
    
      $('#dropbox-modal').on('hidden', function () {
        $('.dropbox-grid').hide()
        $('#dropbox-list tbody').empty();
        $('.dropbox-message').show();          
      });
    
      $('#dropbox').bind('dragenter dragexit dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
      });
      
      $('#dropbox').bind("drop", function (event) {      
        event.stopPropagation();
        event.preventDefault();
        
        self.files = event.originalEvent.dataTransfer.files;
        self.count = self.files.length;
        
        if (self.count > 0) {
          $('.dropbox-message').hide();
          $('.dropbox-grid').show();
          
          var errors = [];
          
          for (var i = 0, errors = []; i < self.files.length; i++) {
            var file = self.files[i];
            var extensions = self.options.allow.join('|');      
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
              fileItem.init(self.options.url + '?' + $.param({
                type: file.type,
                thumbnail: self.options.thumbnail,
                name: name,
                dir: self.options.dir
              }));
            } else {
              errors.push(file.name);
            }
          }
          
          if (errors.length) {
            $('#modal-error').find('.files')
              .text(error.join(', '))
              .end()
              .show();
              
             setTimeout(function () {
                $('#modal-error').hide();
              }, 5000);
          };
        }
      });
    }
  }
})(jQuery);

var FileItem = (function ($) {
  return function (file, name) {
    var self = this;
  
    this.file = file;
    this.name = name;
    
    this.init = function (url) {
      this.$context = $('<tr data-src="">' +
        '<td><input type="checkbox"></td>' + 
        '<td data-column="title"><input type="text" class="title"></td>' +
        '<td data-column="caption"><input type="text" class="caption"></td>' +
        '<td data-column="src"></td>' +
        '<td>' +
          '<div class="progression">' + 
            '<div class="bar">0%</div>' +
          '</div>' + 
        '</td>' +
      '</tr>');
      
      $('#dropbox-list').append(this.$context);
    
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
      $(this.$context).attr('data-src', data.name);
      $('td[data-column="src"]', this.$context).html('<input type="hidden" value="' + data.name + '">' + 
        '<img src="/contents/thumbs/' + data.name + '">');
      
      this.end(data);
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
