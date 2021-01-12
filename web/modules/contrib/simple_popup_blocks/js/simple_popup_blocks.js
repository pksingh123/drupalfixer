(function ($, Drupal) {

  'use strict'

  Drupal.behaviors.simplePopupBlocks = {
    attach: function (context, settings) {
      // Global variables
      var popup_settings = drupalSettings.simple_popup_blocks.settings,
        _html = document.documentElement

      $.each(popup_settings, function (index, values) {

        // Declaring variable inside foreach - so it will not global.
        var modal_class = '',
          block_id = values.identifier,
          visit_counts_arr = values.visit_counts.split(','),
          allow_cookie = true,
          read_cookie = '',
          cookie_val = 1,
          cookie_days = values.cookie_expiry || 100,
          match = 0,
          css_identity = '',
          spb_popup_id = '',
          modal_close_class = '',
          modal_minimize_class = '',
          modal_minimized_class = '',
          layout_class = '',
          class_exists = false,
          delays = '',
          browser_close_trigger = true
        // Always show popup, so prevent from creating cookie
        if (visit_counts_arr.length == 1 && visit_counts_arr[0] == 0) {
          allow_cookie = false
        }
        // Creating cookie
        if (allow_cookie == true) {
          read_cookie = readCookie('spb_' + block_id)
          if (read_cookie) {
            cookie_val = +read_cookie + 1
            createCookie('spb_' + block_id, cookie_val, cookie_days)
          }
          else {
            createCookie('spb_' + block_id, cookie_val, cookie_days)
          }
        }
        // Match cookie
        cookie_val = cookie_val.toString()
        match = $.inArray(cookie_val, visit_counts_arr)
        // Set css selector
        css_identity = '.'
        if (values.css_selector == 1) {
          css_identity = '#'
        }

        // Assign dynamic css classes
        spb_popup_id = 'spb-' + block_id
        modal_class = block_id + '-modal'
        modal_close_class = block_id + '-modal-close'
        modal_minimize_class = block_id + '-modal-minimize'
        modal_minimized_class = block_id + '-modal-minimized'
        layout_class = '.' + modal_class + ' .spb-popup-main-wrapper'
        // Wrap arround elements
        $(css_identity + block_id).
          wrap($('<div class="' + modal_class + '"></div>'))
        // Hide the popup initially
        $('.' + modal_class).hide()
        // Skip the popup based on visit counts settings
        if (match == -1 && allow_cookie == true) {
          return true
        }      
        // Wrap remaining elements
        if ($(css_identity + block_id).closest('.spb-popup-main-wrapper').length) {
          return;
        }
        $(css_identity + block_id).
          wrap($('<div class="spb-popup-main-wrapper"></div>'))
        $('.' + modal_class).
          wrap('<div id="' + spb_popup_id +
            '" class="simple-popup-blocks-global"></div>')
        $(css_identity + block_id).
          before($('<div class="spb-controls"></div>'))        

        // Skip code for non popup pages.
        class_exists = $('#' + spb_popup_id).
          hasClass('simple-popup-blocks-global')
        if (!class_exists) {
          return true
        }
        // Minimize button wrap
        if (values.minimize === "1") {
          $("#"+spb_popup_id + " .spb-controls").
            prepend($('<span class="' + modal_minimize_class +
              ' spb_minimize">-</span>'))
          $('.' + modal_class).
            before($('<span class="' + modal_minimized_class +
              ' spb_minimized"></span>'))
        }
        // Close button wrap
        if (values.close == 1) {
          $("#"+spb_popup_id + " .spb-controls").
            prepend($('<span class="' + modal_close_class +
              ' spb_close">&times;</span>'))
        }
        // Overlay
        if (values.overlay == 1) {
          $('.' + modal_class).addClass('spb_overlay')
        }
        // Inject layout class.
        switch (values.layout) {
          // Top left.
          case '0':
            $(layout_class).addClass('spb_top_left')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Top right.
          case '1':
            $(layout_class).addClass('spb_top_right')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Bottom left.
          case '2':
            $(layout_class).addClass('spb_bottom_left')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Bottom right.
          case '3':
            $(layout_class).addClass('spb_bottom_right')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Center.
          case '4':
            $(layout_class).addClass('spb_center')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Top Center.
          case '5':
            $(layout_class).addClass('spb_top_center')
            $(layout_class).css({})
            break
          // Top bar.
          case '6':
            $(layout_class).addClass('spb_top_bar')
            $(layout_class).css({})
            break
          // Right bar.
          case '7':
            $(layout_class).addClass('spb_bottom_bar')
            $(layout_class).css({})
            break
          // Bottom bar.
          case '8':
            $(layout_class).addClass('spb_left_bar')
            $(layout_class).css({
              'width': values.width,
            })
            break
          // Right bar.
          case '9':
            $(layout_class).addClass('spb_right_bar')
            $(layout_class).css({
              'width': values.width,
            })
            break
        }
        // Automatic trigger with delay
        if (values.trigger_method == 0 && values.delay > 0) {
          delays = values.delay * 1000
          $('.' + modal_class).delay(delays).fadeIn('slow')
		  $(css_identity + block_id).delay(delays).fadeIn('slow')
          if (values.overlay == 1) {
            setTimeout(stopTheScroll, delays)
          }
        }
        // Automatic trigger without delay
        else if (values.trigger_method == 0) {
          $('.' + modal_class).show()
          $(css_identity + block_id).show()
          if (values.overlay == 1) {
            stopTheScroll()
          }
        }
        // Manual trigger
        else if (values.trigger_method == 1) {
          $(values.trigger_selector).click(function () {
            $('.' + modal_class).show()
            $(css_identity + block_id).show()
            if (values.overlay == 1) {
              stopTheScroll()
            }
            return false;
          })
        }
        // Browser close trigger
        else if (values.trigger_method == 2) {
          $(_html).mouseleave(function (e) {
            // Trigger only when mouse leave on top view port
            if (e.clientY > 20) { return }
            // Trigger only once per page
            if (!browser_close_trigger) { return }
            browser_close_trigger = false
            $('.' + modal_class).show()
            $(css_identity + block_id).show()
            if (values.overlay == 1) {
              stopTheScroll()
            }
          })
        }
        // Trigger for close button click
        $('.' + modal_close_class).click(function () {
          $('.' + modal_class).hide()
          startTheScroll()
        })
        // Trigger for minimize button click
        $('.' + modal_minimize_class).click(function () {
          $('.' + modal_class).hide()
          startTheScroll()
          $('.' + modal_minimized_class).show()
        })
        // Trigger for minimized button click
        $('.' + modal_minimized_class).click(function () {
          $('.' + modal_class).show()
          $(css_identity + block_id).show()
          if (values.overlay == 1) {
            stopTheScroll()
          }
          $('.' + modal_minimized_class).hide()
        })
        // Trigger for ESC button click
        if (values.escape == 1) {
          $(document).keyup(function (e) {
            if (e.keyCode == 27) { // Escape key maps to keycode `27`.
              $('.' + modal_class).hide()
              startTheScroll()
              $('.' + modal_minimized_class).show()
            }
          })
        }
      }) // Foreach end.
    }
  } // document.ready end.

  // Remove the scrolling while overlay active
  function stopTheScroll () {
    $('body').css({
      'overflow': 'hidden',
    })
  }

  // Enable the scrolling while overlay inactive
  function startTheScroll () {
    $('body').css({
      'overflow': '',
    })
  }

  // Creating cookie
  function createCookie (name, value, days) {
    if (days > 0) {
      var date = new Date()
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
      var expires = '; expires=' + date.toGMTString()
    }
    else {
      var expires = ''
    }
    document.cookie = name + '=' + value + expires + '; path=/'
  }

  // Reading cookie
  function readCookie (name) {
    var nameEQ = name + '='
    var ca = document.cookie.split(';')
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i]
      while (c.charAt(0) == ' ') {
        c = c.substring(1, c.length)
      }
      if (c.indexOf(nameEQ) == 0) {
        return c.substring(nameEQ.length, c.length)
      }
    }
    return null
  }

})(jQuery, Drupal)
