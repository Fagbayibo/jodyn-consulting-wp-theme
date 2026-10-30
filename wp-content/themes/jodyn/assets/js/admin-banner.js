/* Article banner box (inc/insights.php): pick or remove the photo across the top of the article. */
(function ($) {
  $(document).on('click', '[data-jodyn-banner-choose]', function (e) {
    e.preventDefault();
    var box = $(this).closest('[data-jodyn-banner]');
    var frame = wp.media({
      title: jodynBanner.title,
      button: { text: jodynBanner.button },
      library: { type: 'image' },
      multiple: false
    });
    frame.on('select', function () {
      var img = frame.state().get('selection').first().toJSON();
      var size = (img.sizes && (img.sizes.medium || img.sizes.full)) || img;
      box.find('[data-jodyn-banner-id]').val(img.id);
      box.find('[data-jodyn-banner-preview]').html(
        $('<img>', { src: size.url, alt: '' }).css({ display: 'block', width: '100%', height: 'auto', marginBottom: '8px' })
      );
      box.find('[data-jodyn-banner-choose]').text(jodynBanner.change);
      box.find('[data-jodyn-banner-remove]').prop('hidden', false);
    });
    frame.open();
  });

  $(document).on('click', '[data-jodyn-banner-remove]', function (e) {
    e.preventDefault();
    var box = $(this).closest('[data-jodyn-banner]');
    box.find('[data-jodyn-banner-id]').val('');
    box.find('[data-jodyn-banner-preview]').empty();
    box.find('[data-jodyn-banner-choose]').text(jodynBanner.choose);
    $(this).prop('hidden', true);
  });
})(jQuery);
