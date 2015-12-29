<?php
require_once 'views/_shared/status.html.php';
?>
				<div class="content">
					<a href="/users/admin" title="View all users" class="heading-addon">View All Users</a>
					<h1><?= $User->Nickname ?></h1>
<?php
if ($CurrentUser->hasRole('ADMIN')) { ?>
					<div class="actions">
						<a class="edit" href="/users/edit/<?= strtolower($User->Nickname) ?>" title="Edit this item">Edit</a>
						|
						<a class="delete" href="/users/delete/<?= strtolower($User->Nickname) ?>" title="Delete this item">Delete</a>
					</div>
<?php
}
?>
					<p>
						<div class="label">Created:</div><?= $User->Created ?>
						<br />
						<div class="label">Updated:</div><?= $User->Updated ?>
						<br />
						<div class="label">Last Login:</div><?= $User->LastLogin ?>
						<br />
						<div class="label">E-Mail Address:</div><?= $User->EmailAddress ?>
						<br />
						<div class="label">Nickname:</div><?= $User->Nickname ?>
						<br />
						<div class="label">First Name:</div><?= $User->FirstName ?>
						<br />
						<div class="label">Last Name:</div><?= $User->LastName ?>
					</p>
					<h2>Roles</h2>
					<ul>
<?php
$output = '';
$roles = $User->Roles;
$lastIndex = count($roles) - 1;
if ($lastIndex === -1) {
	$output = '<li>NONE</li>';
} else {
	for ($i = 0; $i <= $lastIndex; $i++) {
		$output .= '<li><a href="/roles/view/' . strtolower($roles[$i]->Type) . '" title="View users with this role">' . $this->sanitize($roles[$i]->Name) . '</a></li>';
	}
}
echo $output;
?>
					</ul>
				</div>
