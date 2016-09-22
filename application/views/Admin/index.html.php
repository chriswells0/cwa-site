				<div class="content">
					<h1>Site Admin</h1>
					<div class="content-body">
<?php
if (!empty($AdminMethods)) {
?>
						<h2>Tools</h2>
						<ul>
<?php
	foreach ($AdminMethods as $name => $url) {
?>
							<li><a href="<?= $url ?>" title="Access this admin tool"><?= $name ?></a></li>
<?php
	}
?>
						</ul>
<?php
}

if (!empty($ModelAdminURLs)) {
?>
						<h2>Data Types</h2>
						<ul>
<?php
	foreach ($ModelAdminURLs as $modelName => $url) {
?>
							<li><a href="<?= $url ?>" title="Access this admin tool"><?= $modelName ?></a></li>
<?php
	}
?>
						</ul>
<?php
}
?>
					</div>
				</div>
