/**
 * @file
 * JS to init Sticky with the proper settings.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.sticky = {
    attach: function (context, settings) {

      var config = settings.sticky;
      
      // Check if user filled out valid selector.
      if ($('html').find(config.selector).length) {
        $(config.selector).sticky({
          selector: config.selector,
          topSpacing: config.top_spacing,
          bottomSpacing: config.bottom_spacing,
          className: config.class_name,
          wrapperClassName: config.wrapper_class_name,
          center: config.center,
          getWidthFrom: config.get_width_from,
          widthFromWrapper: config.width_from_wrapper,
          responsiveWidth: config.responsive_width,
          zIndex: config.z_index
        });
      }
    }
  };
})(jQuery, Drupal);
