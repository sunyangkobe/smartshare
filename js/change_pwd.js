img1 = new Image(16, 16);
img1.src = "images/spinner.gif";

img2 = new Image(220, 19);
img2.src = "images/loader-bar.gif";

window.addEvent('domready', function() {
	$('changeform').addEvent('submit', function(e) {
		// Prevents the default submit event from loading a new page.
		e.stop();

		// Show the spinning indicator when pressing the submit
		// button...
		$('ajax_loading_change').setStyle('display', 'block');

		// Hide the submit button while processing...
		$('confirm').setStyle('display', 'none');
		$('cancel').setStyle('display', 'none');

		// Set the options of the form's Request handler.
		// ("this" refers to the $('login') element).
		this.set('send', {
			onComplete : function(response) {
				$('ajax_loading_change').setStyle('display', 'none');

				response = response.replace(/^\s+|\s+$/g, "");
				if (response == "OK") {
					$msg = '<h2>Done!</h2>' + 
				    "<p>Your password has been changed. Please use " +
				    "the new password to login next time.</p>";
					$('form_container').set('html', $msg);
				} else {
					$('change_response').set('html', response);
					$('confirm').setStyle('display', 'block');
					$('cancel').setStyle('display', 'block');
				}
			}
		});

		// Send the form.
		this.send();
	});
});