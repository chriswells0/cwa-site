<?php
if ($CurrentUser->hasRole('ADMIN')) {
?>
				<div class="actions"><a class="add" href="/users/add">Add a new User</a></div>
<?php
}
?>
			<div class="content">
				<h1>Users</h1>
				<div class="content-body">
					<table>
						<thead>
							<tr>
								<th>Nickname</th>
								<th class="hidden-tablet-portrait">First Name</th>
								<th class="hidden-tablet-portrait">Last Name</th>
								<th class="hidden-phone-portrait">E-Mail Address</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
<?php
if (count($UserList) === 0) {
?>
							<tr><td colspan="5" class="text-center">No users found.</td></tr>
<?php
} else {
	foreach ($UserList as $User) {
		$nickname = strtolower($User->Nickname);
?>
							<tr>
								<td><a href="/users/view/<?= $nickname ?>" title="View details for this item"><?= $User->Nickname ?></a></td>
								<td class="hidden-tablet-portrait"><?= $User->FirstName ?></td>
								<td class="hidden-tablet-portrait"><?= $User->LastName ?></td>
								<td class="hidden-phone-portrait"><?= $User->EmailAddress ?></td>
								<td>
									<div class="actions">
										<a class="edit" href="/users/edit/<?= $nickname ?>" title="Edit this item">Edit</a>
										|
										<a class="delete" href="/users/delete/<?= $nickname ?>" title="Delete this item">Delete</a>
									</div>
								</td>
							</tr>
<?php
	}
}
?>
						</tbody>
					</table>
				</div>
			</div>
<?php
require_once 'views/_shared/pagination.php';

if ($CurrentUser->hasRole('ADMIN')) {
?>
				<div class="actions"><a class="add" href="/users/add">Add a new User</a></div>
<?php
}
?>
