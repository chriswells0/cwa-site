				<div class="content">
					<h1>Contact Me</h1>
					<div class="content-body">
						<div id="loading" class="loading"><img src="/images/loading.gif" /></div>
						<form id="contact-form" name="contact" method="post" action="" data-autoinit="false">
							<div id="form-error" class="error"><?= $this->getStatusMessage() ?></div>
							<div class="form-field">
								<label for="anonymous">Remain Anonymous</label>
								<input type="checkbox" id="anonymous" name="anonymous" value="true" />
								<span id="anon-warn" class="warn">&nbsp;&nbsp;(Note: For obvious reasons, I can't respond to anonymous messages.)</span>
							</div>
							<div id="from">
								<div class="form-field">
									<label for="fromName">Your Name</label>
									<input type="text" id="fromName" name="fromName" value="<?= $fromName ?>" placeholder="Joe Somebody" autofocus autocomplete="name" required minlength="2" />
								</div>
								<div class="form-field">
									<label for="fromEmail">Your Email Address</label>
									<input type="email" id="fromEmail" name="fromEmail" value="<?= $fromEmail ?>" placeholder="you@example.com" autocomplete="email" required minlength="5" />
								</div>
							</div>
							<div class="form-field">
								<label for="subject">Subject</label>
								<input type="text" id="subject" name="subject" value="<?= $subject ?>" required minlength="4" />
							</div>
							<div class="form-field">
								<label for="message">Message</label>
								<div class="form-field">
									<textarea id="message" name="message" required minlength="5"><?= $message ?></textarea>
								</div>
							</div>
<?php if (defined('RECAPTCHA_PUBLIC_KEY')) { ?>
							<div class="form-field">
								<label for="g-recaptcha">No Robots Allowed</label>
								<div class="form-field">
									<div id="g-recaptcha" class="g-recaptcha"></div>
								</div>
							</div>
<?php } ?>
							<div class="buttons">
								<button type="submit" id="send" name="send">Send</button>
							</div>
						</form>
					</div>
				</div>
<script>

var contactForm = new CWA.DOM.Form(document.forms["contact-form"], { autoValidate: false, protectChanges: false }),
	defaultSubject = "Lazy Subject",
	subject = $("#subject");

if (subject.val() === "") subject.val(defaultSubject);
subject.focus(function () {
	if (subject.val() === defaultSubject) subject.val("");
});
subject.blur(function () {
	if ($.trim(subject.val()) === "") subject.val(defaultSubject);
});

$("#anonymous").change(function () {
	if (this.checked) {
		$("#from").hide();
		$("#anon-warn").show();
		$("#fromName, #fromEmail").removeAttr("required");
	} else {
		$("#anon-warn").hide();
		$("#from").show();
		$("#fromName, #fromEmail").attr("required", "");
	}
});
$("#anonymous").prop("checked", <?= ($anonymous ? "true" : "false") ?>).change();

// Form validation. -- cwells
$("#contact-form").on("submit", function (e) {
	e.preventDefault();
	$("#send").attr("disabled", true);

	var anonymous = $("#anonymous").is(":checked"),
		message = $("#message");

	message.val($.trim(message.val()));
	subject.val($.trim(subject.val()));

	contactForm.clearErrors();
	contactForm.validate();

	if (!anonymous && $("#fromName").val() === "") {
		contactForm.setError($("#fromName"), "If you do not wish to remain anonymous, your name is required.");
	}
	if (!anonymous && $("#fromEmail").val() === "") {
		contactForm.setError($("#fromEmail"), "If you do not wish to remain anonymous, your email address is required.");
	}
	if (message.val() === "") {
		contactForm.setError(message, "Nothing worthwhile to say?");
	}
<?php if (defined('RECAPTCHA_PUBLIC_KEY')) { ?>
	if ($("#g-recaptcha-response").val() === "") {
		contactForm.setError($("#g-recaptcha"), "Are you a robot?");
	}
<?php } ?>

	if (contactForm.getErrorCount() === 0) {
		$(this).hide();
		$("#loading").show();
		$.post("contact",
			$(this).serialize(),
			null,
			"json"
		).always(function (data, textStatus, jqXHR) {
			if (data.status && data.status.code === 200) {
				$("#loading").text(data.status.message);
			} else {
				var response = data.responseJSON || { "status": { "message": "An unspecified error has occurred. Please try again." }};
				$("#loading").hide();
				$("#contact-form").show();
				$("#form-error").text(response.status.message);
			}
		});
	}
	$("#send").removeAttr("disabled"); // Enable for invalid data and/or failed submissions.
});

<?php if (defined('RECAPTCHA_PUBLIC_KEY')) { ?>
// Using callback in order to clear the error on successful CAPTCHA submission. -- cwells
function recaptchaLoaded() {
	grecaptcha.render("g-recaptcha", {
		"sitekey": "<?= RECAPTCHA_PUBLIC_KEY ?>",
		"callback": function () {
			contactForm.clearError($("#g-recaptcha"));
		}
	});
}
<?php } ?>

</script>
<?php if (defined('RECAPTCHA_PUBLIC_KEY')) { ?>
<script src="//www.google.com/recaptcha/api.js?onload=recaptchaLoaded&amp;render=explicit" async></script>
<?php } ?>
