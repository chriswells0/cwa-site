				<div class="content">
					<h1>Site Admin</h1>
					<div class="content-body">
<?php
if (!empty($AdminMethods)) {
?>
						<p>The following tools are available to administer this site:</p>
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
						<!-- p>Select an object type to administer:</p -->
						<!-- p>These controllers provide an admin method:</p -->
						<p>You may administer the following types of objects in the database:</p>
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
