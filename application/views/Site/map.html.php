				<div class="content">
					<h1>Site Map</h1>
					<div class="content-body">
						<p>This is a list of the main pages on this site.</p>
<?php
if ($CurrentUser->isLoggedIn()) {
?>
						<p>If you don't have permission to access a page, it will not be listed here.</p>
<?php
} else {
?>
						<p>If a page is only available to logged in users, you must log in to see it here.</p>
<?php
}
?>
						<ul>
<?php
foreach ($Controllers as $controller => $urls) {
	foreach ($urls as $url) {
?>
							<li><a href="<?= $url ?>"><?= $url ?></a></li>
<?php
	}
}
?>
						</ul>
					</div>
				</div>
