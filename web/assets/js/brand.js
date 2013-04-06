var Brand = (function ($) {
  var networksHolder = $('#networks');
  var $addNetworkLink = $('<a href="#" class="add_network_link btn btn-mini">' + Brand.translation['Add a network'] + '</a>');
  var $newNetworkLinkLi = $('<li></li>').prepend($addNetworkLink);
  
  var addressesHolder = $('#addresses');
  var $addAddressLink = $('<a href="#" class="add_address_link btn btn-mini">' + Brand.translation['Add a address'] + '</a>');
  var $newAddressLinkLi = $('<li></li>').prepend($addAddressLink);

  return {
    translation: {
      'delete': 'delete',
      'Add a network': 'Add a network',
      'Add a address': 'Add a address'
    },
  
    init: function () {
      $('#tab-menu a').click(function (event) {
        event.preventDefault();
        $(this).tab('show');
      })
    
      $('#tab-menu a:last').tab('show');
    
      Brand.dettachForms();
      Brand.networks();
      Brand.addresses();
    },
    
    dettachForms: function () {
      var value = $('.container-fluid form').width() * .55;
      var top = $('.container-fluid form').children()
        .first().offset().top;
      
      $('.embedded').each(function (index, embedded) {
        $embedded = $(embedded)
          
        $embedded.find('.node')
          .children()
          .children(':even')
          .addClass('even');
          
        $embedded.find('.node')
          .children()
          .children(':odd')
          .addClass('odd');
      });
    },
    
    networks: function () {
      networksHolder.find('.even, .odd').each(function() {
        Brand.addFormDeleteLink($(this));
      });
    
      // add the "add a tag" anchor and li to the tags ul
      networksHolder.prepend($newNetworkLinkLi);

      // count the current form inputs we have (e.g. 2), use that as the new
      // index when inserting a new item (e.g. 2)
      networksHolder.attr('data-index', networksHolder.find('.even, .odd').length);

      $addNetworkLink.on('click', function (event) {
        // prevent the link from creating a "#" on the URL
        event.preventDefault();

        // add a new tag form (see next code block)
        Brand.addForm(networksHolder, networksHolder.find('.node').children().eq(0));
      });      
    },
    
    addresses: function () {
      addressesHolder.find('.even, .odd').each(function() {
        Brand.addFormDeleteLink($(this));
      });
    
      // add the "add a tag" anchor and li to the tags ul
      addressesHolder.prepend($newAddressLinkLi);

      // count the current form inputs we have (e.g. 2), use that as the new
      // index when inserting a new item (e.g. 2)
      addressesHolder.attr('data-index', addressesHolder.find('.even, .odd').length);

      $addAddressLink.on('click', function (event) {
        // prevent the link from creating a "#" on the URL
        event.preventDefault();

        // add a new tag form (see next code block)
        Brand.addForm(addressesHolder, addressesHolder.find('.node').children().eq(0));
      });     
    },
    
    addForm: function (collectionHolder, $newLinkLi) {
      // Get the data-prototype explained earlier
      var prototype = collectionHolder.attr('data-prototype');

      // get the new index
      var index = collectionHolder.attr('data-index');

      // Replace '__name__' in the prototype's HTML to
      // instead be a number based on how many items we have
      var newForm = prototype.replace(/__name__/g, index);

      // increase the index with one for the next item
      collectionHolder.attr('data-index', Number(index) + 1);
      
      var className = $newLinkLi.children().first().attr('class') == 'even' ? 'odd' : 'even';
      
      // Display the form in the page in an li, before the "Add a tag" link li
      var $newFormLi = $('<div class="' + (className) + '"></div>').append(newForm);
      $newLinkLi.prepend($newFormLi);
      
      Brand.addFormDeleteLink($newFormLi);
    },
    
    addFormDeleteLink: function ($formLi) {
      var $removeFormA = $('<a href="#" class="btn btn-mini btn-danger">' + Brand.translation['delete'] + '</a>');
      $formLi.append($removeFormA);

      $removeFormA.on('click', function (event) {
          // prevent the link from creating a "#" on the URL
          event.preventDefault();

          // remove the li for the tag form
          $formLi.remove();
      });
    }
  }
})(jQuery);