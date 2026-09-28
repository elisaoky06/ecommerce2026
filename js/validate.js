// Attaches a submit listener to the registration form. Runs regex
// validation on every field before the form is allowed to actually
// submit. If anything fails, inline error messages are shown next to
// the offending field and submission is blocked. If everything passes,
// registerCustomer() (defined in customer.js) is called to actually
// send the data to the server.
document.addEventListener("DOMContentLoaded", function () {
	var form = document.getElementById("registerForm");

	if (!form) {
		return; // this script may load on pages without a register form
	}

	var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	var phoneRegex = /^[0-9+\-\s]{7,15}$/;

	form.addEventListener("submit", function (e) {
		// Always prevent the browser's default submission - registration
		// is sent via fetch() in customer.js, not a native form POST.
		e.preventDefault();

		// Clear every error span before re-checking, so old errors don't
		// linger next to fields that are now fixed.
		var errorSpans = form.querySelectorAll(".error");
		errorSpans.forEach(function (span) {
			span.textContent = "";
		});

		var isValid = true;

		function showError(fieldId, message) {
			var span = document.getElementById("error_" + fieldId);
			if (span) {
				span.textContent = message;
			}
			isValid = false;
		}

		var name = document.getElementById("customer_name").value.trim();
		var email = document.getElementById("customer_email").value.trim();
		var pass = document.getElementById("customer_pass").value.trim();
		var country = document.getElementById("customer_country").value.trim();
		var city = document.getElementById("customer_city").value.trim();
		var contact = document.getElementById("customer_contact").value.trim();

		if (!name) {
			showError("customer_name", "Name is required.");
		}

		if (!email) {
			showError("customer_email", "Email is required.");
		} else if (!emailRegex.test(email)) {
			showError("customer_email", "Enter a valid email address.");
		}

		if (!pass) {
			showError("customer_pass", "Password is required.");
		}

		if (!country) {
			showError("customer_country", "Country is required.");
		}

		if (!city) {
			showError("customer_city", "City is required.");
		}

		if (!contact) {
			showError("customer_contact", "Contact number is required.");
		} else if (!phoneRegex.test(contact)) {
			showError("customer_contact", "Enter a valid phone number.");
		}

		if (!isValid) {
			return; // stop here - do not call registerCustomer()
		}

		// All fields passed validation - show a loading state, then
		// hand off to registerCustomer() (in customer.js) to send the
		// actual request.
		var submitBtn = document.getElementById("registerSubmitBtn");
		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.textContent = "Registering...";
		}

		registerCustomer(function () {
			// Callback runs once the fetch in registerCustomer() finishes,
			// so the button can be restored either way.
			if (submitBtn) {
				submitBtn.disabled = false;
				submitBtn.textContent = "Register";
			}
		});
	});
});