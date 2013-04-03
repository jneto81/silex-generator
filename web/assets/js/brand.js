var Brand = (function ($) {
  var networksHolder = $('ul.networks');
  var $addNetworkLink = $('<a href="#" class="add_network_link btn btn-mini">Add a network</a>');
  var $newNetworkLinkLi = $('<li></li>').append($addNetworkLink);
  
  var addressesHolder = $('ul.addresses');
  var $addAddressLink = $('<a href="#" class="add_address_link btn btn-mini">Add a address</a>');
  var $newAddressLinkLi = $('<li></li>').append($addAddresskLink);

  return {
  
    init: function () {
      Brand.networks();
      Brand.addresses();
    },
    
    networks: function () {
      networksHolder.find('li').each(function() {
        Brand.addFormDeleteLink($(this));
      });
    
      // add the "add a tag" anchor and li to the tags ul
      networksHolder.append($newNetworkLinkLi);

      // count the current form inputs we have (e.g. 2), use that as the new
      // index when inserting a new item (e.g. 2)
      networksHolder.data('index', networksHolder.find(':input').length);

      $addNetworkLink.on('click', function (event) {
        // prevent the link from creating a "#" on the URL
        event.preventDefault();

        // add a new tag form (see next code block)
        Brand.addForm(networksHolder, $newNetworkLinkLi);
      });      
    },
    
    addresses: function () {
      addressesHolder.find('li').each(function() {
        Brand.addFormDeleteLink($(this));
      });
    
      // add the "add a tag" anchor and li to the tags ul
      addressesHolder.append($newAddressLinkLi);

      // count the current form inputs we have (e.g. 2), use that as the new
      // index when inserting a new item (e.g. 2)
      addressesHolder.data('index', addressesHolder.find(':input').length);

      $addAddressLink.on('click', function (event) {
        // prevent the link from creating a "#" on the URL
        event.preventDefault();

        // add a new tag form (see next code block)
        Brand.addForm(addressesHolder, $newAddressLinkLi);
      });     
    },
    
    addForm: function (collectionHolder, $newLinkLi) {
      // Get the data-prototype explained earlier
      var prototype = collectionHolder.data('prototype');

      // get the new index
      var index = collectionHolder.data('index');

      // Replace '__name__' in the prototype's HTML to
      // instead be a number based on how many items we have
      var newForm = prototype.replace(/__name__/g, index);

      // increase the index with one for the next item
      collectionHolder.data('index', index + 1);

      // Display the form in the page in an li, before the "Add a tag" link li
      var $newFormLi = $('<li></li>').append(newForm);
      $newLinkLi.before($newFormLi);
      
      brand.addFormDeleteLink($newFormLi);
    },
    
    addFormDeleteLink: function ($formLi) {
      var $removeFormA = $('<a href="#" class="btn btn-mini btn-danger">delete</a>');
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