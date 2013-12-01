/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * common js functions
 *
 * @author Kobe Sun
 *
 */

function CheckPassword(password) {
	var score = 1;

	if (password.length < 1)
		return 0;

	if (password.length < 2)
		return 1;

	if (password.length >= 4)
		score++;
	if (password.length >= 6)
		score++;
	if (password.length >= 8)
		score++;
	if (password.length >= 10)
		score++;
	if (password.length >= 12)
		score++;
	if (password.match(/\d+/))
		score++;
	if (password.match(/[a-z]/) && password.match(/[A-Z]/))
		score++;
	if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/))
		score++;

	return score;
}

function PasswordChanged(field) {
	var span = document.getElementById("PasswordStrength");
	span.innerHTML = "<img src='images/pwd/password_strength_"
			+ CheckPassword(field.value) + ".gif' />";
}

function run() {
	var i = document.getElementById("sec");
	if (i.innerHTML * 1 > 0) {
		i.innerHTML = i.innerHTML * 1 - 1;
	}
}
