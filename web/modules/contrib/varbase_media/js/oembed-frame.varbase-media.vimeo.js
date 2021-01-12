/**
 * @file
 * Behaviors of Viemo player in the Default OEmbed iframe.
 */

function ready(fn) {
  if (document.readyState !== "loading") {
    fn();
  } else if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", fn);
  } else {
    document.attachEvent("onreadystatechange", function() {
      if (document.readyState !== "loading") fn();
    });
  }
}

const tag = document.createElement("script");
tag.src = "//player.vimeo.com/api/player.js";
const firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

ready(function() {
  const mediaIframe = document.querySelector("iframe");
  mediaIframe.setAttribute("id", "media-oembed-iframe");

  let playerConfgured = false;
  let vimeoPlayer;

  function actionProcessor(evt) {
    // Manage Vimeo video.
    if (evt.data === "play") {
      if (!playerConfgured) {
        const vimeoIframe = document.querySelector('iframe[src*="vimeo.com"]');

        vimeoPlayer = new window.Vimeo.Player(vimeoIframe);
        playerConfgured = true;
      }

      vimeoPlayer.ready().then(function() {
        vimeoPlayer.getPaused().then(function(paused) {
          if (paused) {
            vimeoPlayer.play();
          }
        });
      });
    }
  }

  // Setup the event listener for messaging.
  if (window.addEventListener) {
    window.addEventListener("message", actionProcessor, false);
  } else {
    window.attachEvent("onmessage", actionProcessor);
  }
});
