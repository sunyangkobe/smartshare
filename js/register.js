/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * register js ajax event
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
	$('regform').addEvent(
			'submit',
			function(e) {
				// Prevents the default submit event from loading a new page.
				e.stop();

				// Show the spinning indicator when pressing the submit
				// button...
				$('ajax_loading_reg').setStyle('display', 'block');

				// Hide the submit button while processing...
				$('register').setStyle('display', 'none');
				$('reset').setStyle('display', 'none');

				// Set the options of the form's Request handler.
				// ("this" refers to the $('login') element).
				this.set('send', {
					onComplete : function(response) {
						$('ajax_loading_reg').setStyle('display', 'none');

						response = response.replace(/^\s+|\s+$/g, "");
						if (response.match(/^<div id=\"error_notification\"/i)) {
							$('siimage').set(
									'src',
									'account/securimage_show.php?sid='
											+ Math.random());
							$('register_response').set('html', response);
							// Show the login button
							$('register').setStyle('display', 'block');
							$('reset').setStyle('display', 'block');
						} else {
							$msg = '<h2>Congratulation! Almost done...</h2>' + 
							    "<p>A confirmation email has been sent to " +
								response + ", please follow the link to " + 
								"activate your account...</p>";
							$('form_container').set('html', $msg);
						}
					}
				});

				// Send the form.
				this.send();
			});
});