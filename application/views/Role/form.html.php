<?php
require_once 'views/_shared/status.html.php';
?>
			<div class="content">
				<h1>Role</h1>
				<div class="content-body">
					<form id="role-form" action="/roles/save" method="post">
						<input type="hidden" name="ID" id="ID" value="<?= $Role->ID ?>" />
						<div class="form-field">
							<label for="Type">Type</label>
							<input type="text" name="Type" id="Type" size="10" value="<?= $Role->Type ?>" placeholder="STATICTYPE" autofocus required minlength="2" maxlength="10" />
						</div>
						<div class="form-field">
							<label for="Name">Name</label>
							<input type="text" name="Name" id="Name" size="15" value="<?= $Role->Name ?>" placeholder="Proper Name" required minlength="2" maxlength="30" />
						</div>
						<div class="form-field">
							<label for="Description">Description</label>
							<div class="form-field">
								<textarea name="Description" id="Description" placeholder="Description of this role and/or its permissions (100 characters max)" maxlength="100"><?= $Role->Description ?></textarea>
							</div>
						</div>
						<div class="form-field">
							<label for="Users[]">Users</label>
							<select name="Users[]" id="Users[]" multiple="multiple" size="5">
<?php
foreach($Users as $User) {
	echo "<option value=\"$User->ID\"" . (in_array($User->ID, $RoleUserIDs) ? ' selected="selected"' : '') . " title=\"$User->Nickname\">$User->FirstName $User->LastName</option>";
}
?>
							</select>
						</div>
						<div class="buttons">
							<button id="submit" type="submit">Save</button>
							<button type="button" onclick="document.location='/roles/admin'; return false;">Cancel</button>
						</div>
					</form>
				</div>
			</div>
