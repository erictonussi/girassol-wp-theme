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

    var form = $('#contactForm');

    form.submit(function (event) {
      // Stop the browser from submitting the form.
      event.preventDefault();

      // Serialize the form data.
      var formData = $(form).serialize();
      // formData.action = 'index_contact_form';

      // Submit the form using AJAX.
      $.ajax({
          type: 'POST',
          url: '/wp-admin/admin-ajax.php?action=index_contact_form',
          data: formData,
      })
        .then(function (result) {
          switch (result) {
            case 'mail_sent':
              alert('Email enviado com sucesso');
              form[0].reset();
              break;
            case 'mail_not_sent':
              alert('Falha ao enviar email');
              break;
          }
        });

    });

  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
