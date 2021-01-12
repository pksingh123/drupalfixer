/**
 * @file
 * Provides Colorbox integration.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Attaches slick behavior to HTML element identified by .slick--colorbox.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.slickColorbox = {
    attach: function (context) {
      $(context).on('cbox_open', function () {
        Drupal.slickColorbox.set('slickPause');
      });

      $(context).on('cbox_load', function () {
        Drupal.slickColorbox.set('setPosition');
      });

      $(context).on('cbox_closed', function () {
        Drupal.slickColorbox.set('slickPlay');
      });

      $('.slick--colorbox', context).once('slick-colorbox').each(doSlickColorbox);
    }
  };

  /**
   * Adds each slide a reliable ordinal to get correct current with clones.
   *
   * @param {int} i
   *   The index of the current element.
   * @param {HTMLElement} elm
   *   The slick HTML element.
   */
  function doSlickColorbox(i, elm) {
    $('.slick__slide', elm).each(function (j, el) {
      $(el).attr('data-delta', j);
    });
  }

  /**
   * Slick Colorbox utility functions.
   *
   * @namespace
   */
  Drupal.slickColorbox = Drupal.slickColorbox || {

    /**
     * Sets method related to Slick methods.
     *
     * @name set
     *
     * @param {string} method
     *   The method to apply to .slick__slider element.
     */
    set: function (method) {
      var $box = $.colorbox.element();
      var $slider = $box.closest('.slick__slider');
      var $wrap = $slider.closest('.slick-wrapper');
      var curr;

      if ($slider.length) {
        // Slick is broken after colorbox close, do setPosition manually.
        if (method === 'setPosition') {
          // Cannot use dataSlickIndex which maybe negative with slick clones.
          curr = Math.abs($box.closest('.slick__slide').data('delta'));
          if (isNaN(curr)) {
            curr = 0;
          }

          if ($wrap.length) {
            var $thumb = $wrap.find('.slick--thumbnail .slick__slider');
            $thumb.slick('slickGoTo', curr);
          }
          $slider.slick('slickGoTo', curr);
        }
        else if (method === 'slickPlay') {
          var slick = $slider.slick('getSlick');
          if (slick && slick.options.autoPlay) {
            $slider.slick(method);
          }
          // Fixes Firefox and IE width recalculation after closing the colorbox modal.
          $slider.slick('refresh');
        }
        else {
          $slider.slick(method);
        }
      }
    }
  };

}(jQuery, Drupal));
