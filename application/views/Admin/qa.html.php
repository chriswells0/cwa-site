				<div class="content">
					<h1>QA Assistant</h1>
					<div class="content-body">
<?php
foreach ($Controllers as $controller => $methods) {
?>
						<h2><?= str_replace('Controller', ' Controller', $controller) ?></h2>
						<table>
							<thead>
								<tr>
									<th>URL</th>
									<th class="hidden-tablet-portrait">Parameter</th>
									<th>Roles</th>
								</tr>
							</thead>
							<tbody>
<?php
	foreach ($methods as $method) {
?>
								<tr>
									<td><a href="<?= $method['url'] ?>" target="_blank" title="Opens in new window"><?= $method['url'] ?></a></td>
									<td class="hidden-tablet-portrait"><?= implode(', ', $method['parameters']) ?></td>
									<td>
<?php
		if (is_null($method['roles'])) {
			echo 'None';
		} else if (empty($method['roles'])) {
			echo 'Must Log In';
		} else {
			foreach ($method['roles'] as $index => $role) {
				echo "<a href=\"/roles/view/" . strtolower($role) . "\" target=\"_blank\" title=\"Opens in new window\">$role</a>";
				if (($index + 1) !== count($method['roles'])) {
					echo ', ';
				}
			}
		}
?>
									</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>
<?php
}
?>
					</div>
				</div>
