<?php
require_once 'views/_shared/status.html.php';
?>
			<div class="content">
				<h1>User</h1>
				<div class="content-body">
					<form id="user-form" action="/users/save" method="post">
						<input type="hidden" name="ID" id="ID" value="<?= $User->ID ?>" />
						<div class="form-field">
							<label for="EmailAddress">E-Mail Address</label>
							<input type="email" name="EmailAddress" id="EmailAddress" class="email" size="25" value="<?= $User->EmailAddress ?>" placeholder="johndoe@example.com" autofocus autocomplete="email" required minlength="5" maxlength="50" />
						</div>
						<div class="form-field">
							<label for="Nickname">Username</label>
							<input type="text" name="Nickname" id="Nickname" size="20" value="<?= $User->Nickname ?>" placeholder="johndoe" autocomplete="username" required minlength="3" maxlength="15" />
						</div>
						<div id="setpassword-field" class="form-field">
							<label for="SetPassword">Set Password</label>
							<input type="checkbox" name="SetPassword" id="SetPassword" value="yes" />
						</div>
						<div id="password-fields">
							<div class="form-field">
								<label for="Password">Password</label>
								<input type="password" name="Password" id="Password" size="15" value="" autocomplete="new-password" required minlength="10" maxlength="100" />
							</div>
							<div class="form-field">
								<label for="ConfirmPassword">Confirm Password</label>
								<input type="password" name="ConfirmPassword" id="ConfirmPassword" size="15" value="" required minlength="10" maxlength="100" data-must-match="Password" />
							</div>
						</div>
						<div class="form-field">
							<label for="FirstName">First Name</label>
							<input type="text" name="FirstName" id="FirstName" size="15" value="<?= $User->FirstName ?>" placeholder="John" autocomplete="given-name" maxlength="15" />
						</div>
						<div class="form-field">
							<label for="LastName">Last Name</label>
							<input type="text" name="LastName" id="LastName" size="15" value="<?= $User->LastName ?>" placeholder="Doe" autocomplete="family-name" maxlength="15" />
						</div>
						<div class="form-field">
							<label for="Roles[]">Roles</label>
							<input type="hidden" name="Roles[]" value="" />
							<select name="Roles[]" id="Roles[]" multiple="multiple" size="5">
<?php
foreach($Roles as $Role) {
	echo "<option value=\"$Role->ID\"" . (in_array($Role->ID, $UserRoleIDs) ? ' selected="selected"' : '') . " title=\"$Role->Description\">$Role->Name</option>";
}
?>
							</select>
						</div>
						<div class="buttons">
							<button id="submit" type="submit">Save</button>
							<button type="button" onclick="document.location='/users/admin'; return false;">Cancel</button>
						</div>
					</form>
				</div>
			</div>
<script>
$("#SetPassword").change(function () {
	if (this.checked) {
		$("#password-fields").show();
		$("#Password, #ConfirmPassword").attr("required", "");
	} else {
		$("#password-fields").hide();
		$("#Password, #ConfirmPassword").removeAttr("required");
	}
});

<?php if (empty($User->ID)) { // New user. ?>
	$("#SetPassword").prop("checked", true).change();
	$("#setpassword-field").hide();
<?php } else if ($User->SetPassword === 'yes') { // Previously attempted to change the password. ?>
	$("#SetPassword").prop("checked", true).change();
<?php } else { // Editing existing user. ?>
	$("#SetPassword").prop("checked", false).change();
	$("#password-fields").hide();
<?php } ?>
</script>
