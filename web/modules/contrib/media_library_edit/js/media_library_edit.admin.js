(function ($, Drupal, window) {

  Drupal.behaviors.MediaLibraryWidgetWarn = {
    attach: function attach(context) {
      // Override the existing warning from media_library/js/media_library.ui.js
      // to disable for the edit link.
      $('.js-media-library-item a[href]:not(.media-library-edit__link)', context).once('media-library-warn-link').on('click', function (e) {
        var message = Drupal.t('Unsaved changes to the form will be lost. Are you sure you want to leave?');
        var confirmation = window.confirm(message);
        if (!confirmation) {
          e.preventDefault();
        }
      });
    }
  };

})(jQuery, Drupal, window);
