<?php
if ($CurrentUser->hasRole('ADMIN')) {
?>
				<div class="actions"><a class="add" href="/roles/add">Add a new Role</a></div>
<?php
}
?>
			<div class="content">
				<h1>Roles</h1>
				<div class="content-body">
					<table>
						<thead>
							<tr>
								<th>Name</th>
								<th class="hidden-phone-portrait">Description</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
<?php
if (count($RoleList) === 0) {
?>
							<tr><td colspan="3" class="text-center">No roles found.</td></tr>
<?php
} else {
	foreach ($RoleList as $Role) {
?>
							<tr>
								<td><a href="/roles/view/<?= strtolower($Role->Type) ?>" title="View details for this item"><?= $Role->Name ?></a></td>
								<td class="hidden-phone-portrait"><?= $Role->Description ?></td>
								<td>
									<div class="actions">
										<a class="edit" href="/roles/edit/<?= strtolower($Role->Type) ?>" title="Edit this item">Edit</a>
										|
										<a class="delete" href="/roles/delete/<?= strtolower($Role->Type) ?>" title="Delete this item">Delete</a>
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
				<div class="actions"><a class="add" href="/roles/add">Add a new Role</a></div>
<?php
}
?>
