export default {
  init() { //console.log(123);
    // Select all links with hashes
    $('a[href*="#"]')
      // Remove links that don't actually link to anything
      .not('[href="#"]')
      .not('[href="#0"]')
      .click(function(event) { //console.log(event);
        // On-page links
        if (
          location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
          && location.hostname == this.hostname
        ) {
          // Figure out element to scroll to
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
          // Does a scroll target exist?
          if (target.length) {
            // Only prevent default if animation is actually gonna happen
            event.preventDefault();

            $('html, body').animate({
              scrollTop: target.offset().top,
            }, 400, function() {
            });
          }
        }
      });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
