/* Media Library pickers (jodyn_media_picker(), inc/authors.php): the Article banner and the
   author photo. Labels come from the picker's data attributes. */
(function ($) {
  function reset(box) {
    box.find('[data-jodyn-media-id]').val('');
    box.find('[data-jodyn-media-preview]').empty();
    box.find('[data-jodyn-media-choose]').text(box.data('choose'));
    box.find('[data-jodyn-media-remove]').prop('hidden', true);
  }

  $(document).on('click', '[data-jodyn-media-choose]', function (e) {
    e.preventDefault();
    var box = $(this).closest('[data-jodyn-media]');
    var frame = wp.media({
      title: box.data('title'),
      button: { text: box.data('button') },
      library: { type: 'image' },
      multiple: false
    });
    frame.on('select', function () {
      var img = frame.state().get('selection').first().toJSON();
      var size = (img.sizes && (img.sizes[box.data('size')] || img.sizes.medium || img.sizes.full)) || img;
      box.find('[data-jodyn-media-id]').val(img.id);
      box.find('[data-jodyn-media-preview]').html(
        $('<img>', { src: size.url, alt: '' }).css({ display: 'block', maxWidth: '100%', height: 'auto', marginBottom: '8px' })
      );
      box.find('[data-jodyn-media-choose]').text(box.data('change'));
      box.find('[data-jodyn-media-remove]').prop('hidden', false);
    });
    frame.open();
  });

  $(document).on('click', '[data-jodyn-media-remove]', function (e) {
    e.preventDefault();
    reset($(this).closest('[data-jodyn-media]'));
  });

  // Posts → Authors adds a new author without reloading: empty the picker for the next one.
  $(document).ajaxSuccess(function (event, xhr, settings) {
    if (settings.data && String(settings.data).indexOf('action=add-tag') !== -1) {
      $('#addtag [data-jodyn-media]').each(function () { reset($(this)); });
    }
  });
})(jQuery);
