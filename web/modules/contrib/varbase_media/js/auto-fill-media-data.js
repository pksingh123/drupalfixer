/**
 * @file
 * Behaviors Varbase Auto Fill Media data scripts.
 */

(function($, _, Drupal) {
  Drupal.behaviors.autoFillMediaData = {
    attach: function() {
      // Save the default value on loading the page.
      let defaultName = $("input[name='name[0][value]']").val();
      let defaultAlt = $("input[name='field_media_image[0][alt]']").val();
      let defaultTitle = $("input[name='field_media_image[0][title]']").val();

      // When we do change the media name.
      $("input[name='name[0][value]']").keyup(function() {
        // And the default alt was empty.
        if (defaultAlt === "") {
          // Then copy then value of the media name
          $("input[name='field_media_image[0][alt]']").val(this.value);
        }

        // And the default alt was empty.
        if (defaultTitle === "") {
          // Then copy then value of the media name
          $("input[name='field_media_image[0][title]']").val(this.value);
        }

        // And reset the default value for the media name.
        defaultName = $("input[name='name[0][value]']").val();
      });
      $("input[name='name[0][value]']").blur(function() {
        defaultName = $("input[name='name[0][value]']").val();
        defaultAlt = $("input[name='field_media_image[0][alt]']").val();
        defaultTitle = $("input[name='field_media_image[0][title]']").val();
      });

      // When we do change the field media image alt value.
      $("input[name='field_media_image[0][alt]']").keyup(function() {
        // And the default media name was empty.
        if (defaultName === "") {
          // Then update the media name with the alt value.
          $("input[name='name[0][value]']").val(this.value);
        }

        // And the default title was empty.
        if (defaultTitle === "") {
          // Then update the media title with the alt value.
          $("input[name='field_media_image[0][title]']").val(this.value);
        }

        // And reset the default value for the media alt.
        defaultAlt = $("input[name='field_media_image[0][alt]']").val();
      });
      $("input[name='field_media_image[0][alt]']").blur(function() {
        defaultName = $("input[name='name[0][value]']").val();
        defaultAlt = $("input[name='field_media_image[0][alt]']").val();
        defaultTitle = $("input[name='field_media_image[0][title]']").val();
      });

      // When we do change the media title.
      $("input[name='field_media_image[0][title]']").keyup(function() {
        // And the default media name was empty.
        if (defaultName === "") {
          // Then update the media name with the title value.
          $("input[name='name[0][value]']").val(this.value);
        }

        // And the default media alt was empty.
        if (defaultAlt === "") {
          // Then update the media alt with the title value.
          $("input[name='field_media_image[0][alt]']").val(this.value);
        }

        // And reset the default value for the media title.
        defaultTitle = $("input[name='field_media_image[0][title]']").val();
      });
      $("input[name='field_media_image[0][title]']").blur(function() {
        defaultName = $("input[name='name[0][value]']").val();
        defaultAlt = $("input[name='field_media_image[0][alt]']").val();
        defaultTitle = $("input[name='field_media_image[0][title]']").val();
      });
    }
  };
})(window.jQuery, window._, window.Drupal);
