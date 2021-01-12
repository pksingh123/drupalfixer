(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.length_indicator = {
    attach: function (context, settings) {
      $(context)
        .find('[length-indicator-enabled]')
        .once('length-indicator')
        .each(function (index, element) {
          var $el = $(element);
          var total = $el.data('length-indicator-total');

          new Indicator($el, $el.closest('.form-wrapper'), total);
        });
    }
  };

  function Indicator($el, $context, total) {
    this.$el = $el;

    this.total = total;

    this.allIndicators = $context.find('.length-indicator__indicator');
    this.cursor = $context.find('.length-indicator__cursor');

    var self = this;
    this.$el.on('input', function (e) {
      self.setCursorAndActiveIndicator();
    });
    this.setCursorAndActiveIndicator();
  }

  Indicator.prototype.setCursorAndActiveIndicator = function () {
    var length = this.$el.val().length;
    var position = (length / this.total) * 100;

    var positionDir = 'left';
    if ($('html').attr('dir') === 'rtl') {
      positionDir = 'right';
    }

    position = position < 100 ? position : 100;
    this.cursor.css(positionDir, position + '%');

    this.allIndicators.removeClass('is-active');

    var coloredIndicator = this.allIndicators.eq(0);
    for (var i = 1; i < this.allIndicators.length; i++) {
      var indicator = this.allIndicators.eq(i);
      if (length >= indicator.data('pos')) {
        coloredIndicator = indicator;
      }
      else {
        break;
      }
    }
    coloredIndicator.addClass('is-active');
  };

})(Drupal, jQuery);
