<?php
require_once 'views/_shared/status.html.php';
?>
				<div id="role" class="content">
					<a href="/roles/admin" title="View all roles" class="heading-addon">View All Roles</a>
					<h1><?= $Role->Name ?></h1>
					<div class="actions">
						<a class="edit" href="/roles/edit/<?= strtolower($Role->Type) ?>" title="Edit this item">Edit</a>
						|
						<a class="delete" href="/roles/delete/<?= strtolower($Role->Type) ?>" title="Delete this item">Delete</a>
					</div>
					<p>
						<div class="label">Created:</div><?= $Role->Created ?>
						<br />
						<div class="label">Updated:</div><?= $Role->Updated ?>
						<br />
						<div class="label">Type:</div><?= $Role->Type ?>
						<br />
						<div class="label">Name:</div><?= $Role->Name ?>
						<br />
						<div class="label">Description:</div><?= $Role->Description ?>
					</p>
					<h2>Users in Role</h2>
					<ul>
<?php
$output = '';
$users = $Role->Users;
$lastIndex = count($users) - 1;
if ($lastIndex === -1) {
	$output = '<li>NONE</li>';
} else {
	for ($i = 0; $i <= $lastIndex; $i++) {
		$output .= '<li><a href="/users/view/' . strtolower($users[$i]->Nickname) . '" title="View user">' . $this->sanitize($users[$i]->Nickname) . '</a></li>';
	}
}
echo $output;
?>
					</ul>
				</div>
