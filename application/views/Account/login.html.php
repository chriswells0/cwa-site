<?php
require_once 'views/_shared/status.html.php';
?>
				<div class="content">
					<h1>Log In</h1>
					<div class="content-body">
						<form name="login" id="login" action="" method="post" data-autoinit="false">
							<div class="form-field">
								<label for="Nickname">Username</label>
								<input type="text" name="Nickname" id="Nickname" value="" autofocus autocomplete="username" required minlength="3" maxlength="15" />
							</div>
							<div class="form-field">
								<label for="Password">Password</label>
								<input type="password" name="Password" id="Password" value="" autocomplete="current-password" required minlength="8" />
							</div>
							<div class="buttons">
								<button type="submit" name="submit" value="login">Log In</button>
							</div>
						</form>
					</div>
				</div>
<script>
var loginForm = new CWA.DOM.Form(document.forms["login"], { protectChanges: false });
</script>
