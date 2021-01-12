/**
 * @file
 * Behaviors Varbase Video Player general scripts.
 */

(function($, _, Drupal) {
  Drupal.behaviors.varbaseVideoPlayer = {
    attach: function() {
      $(".js-video-player-icon").on("click", function() {
        $(this).fadeOut(500);

        // Locally Hosted Video.
        // ---------------------------------------------------------------------
        if (
          $(this)
            .closest(".field.field--type-entity-reference")
            .find(".media--type-video video").length > 0
        ) {
          $(this)
            .closest(".field.field--type-entity-reference")
            .find(".media--type-video video")
            .get(0)
            .play();
        }

        if (
          $(this)
            .closest(".embedded-entity")
            .find(".media--type-video video").length > 0
        ) {
          $(this)
            .closest(".embedded-entity")
            .find(".media--type-video video")
            .get(0)
            .play();
        }
        // ---------------------------------------------------------------------

        // Remote Youtube Video.
        // ---------------------------------------------------------------------
        if (
          $(this)
            .closest(".field.field--type-entity-reference")
            .find('.media--type-remote-video iframe[src*="youtube.com"]')
            .length > 0
        ) {
          const closestYoutubeIframe = $(this)
            .closest(".field.field--type-entity-reference")
            .find('.media--type-remote-video iframe[src*="youtube.com"]')
            .get(0).contentWindow;
          closestYoutubeIframe.postMessage("play", "*");
        }

        if (
          $(this)
            .closest(".embedded-entity")
            .find('.media--type-remote-video iframe[src*="youtube.com"]')
            .length > 0
        ) {
          const closestYoutubeIframe = $(this)
            .closest(".embedded-entity")
            .find('.media--type-remote-video iframe[src*="youtube.com"]')
            .get(0).contentWindow;
          closestYoutubeIframe.postMessage("play", "*");
        }
        // ---------------------------------------------------------------------

        // Remote Vimeo Video.
        // ---------------------------------------------------------------------
        if (
          $(this)
            .closest(".field.field--type-entity-reference")
            .find('.media--type-remote-video iframe[src*="vimeo.com"]').length >
          0
        ) {
          const closestVimeoIframe = $(this)
            .closest(".field.field--type-entity-reference")
            .find('.media--type-remote-video iframe[src*="vimeo.com"]')
            .get(0).contentWindow;
          closestVimeoIframe.postMessage("play", "*");
        }

        if (
          $(this)
            .closest(".embedded-entity")
            .find('.media--type-remote-video iframe[src*="vimeo.com"]').length >
          0
        ) {
          const closestVimeoIframe = $(this)
            .closest(".embedded-entity")
            .find('.media--type-remote-video iframe[src*="vimeo.com"]')
            .get(0).contentWindow;
          closestVimeoIframe.postMessage("play", "*");
        }
        // ---------------------------------------------------------------------
      });
    }
  };
})(window.jQuery, window._, window.Drupal);
