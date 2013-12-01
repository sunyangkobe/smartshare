/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * login js ajax event
 *
 * @author Kobe Sun
 *
 */

// Preload Images
img1 = new Image(16, 16);
img1.src = "images/spinner.gif";

img2 = new Image(220, 19);
img2.src = "images/loader-bar.gif";

window.addEvent('domready', function() {
	$('loginform').addEvent('submit', function(e) {
		// Prevents the default submit event from loading a new page.
		e.stop();

		// Show the spinning indicator when pressing the submit
		// button...
		$('ajax_loading_login').setStyle('display', 'block');

		// Hide the submit button while processing...
		$('login').setStyle('display', 'none');
		$('forgot').setStyle('display', 'none');

		// Set the options of the form's Request handler.
		// ("this" refers to the $('login') element).
		this.set('send', {
			onComplete : function(response) {
				$('ajax_loading_login').setStyle('display', 'none');

				response = response.replace(/^\s+|\s+$/g, "");
				if (response == "OK") {
					window.location.reload();
				} else {
					$('login_response').set('html', response);
					// Show the login button
					$('login').setStyle('display', 'block');
					$('forgot').setStyle('display', 'block');
				}
			}
		});

		// Send the form.
		this.send();
	});
});