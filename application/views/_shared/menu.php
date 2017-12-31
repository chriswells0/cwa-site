			<div id="navigation" class="sidebar hidden-phone hidden-print">
				<ul id="menu-primary" class="menu">
					<li><a id="menu-home" href="/site" title="Return to Home" class="menu-item <?= $_GET['controller'] === 'site' && $_GET['method'] === 'index' ? 'selected' : '' ?>">Home</a></li>
					<li><a id="menu-about" href="/site/about" title="Learn more about me and my site" class="menu-item <?= $_GET['method'] === 'about' ? 'selected' : '' ?>">About</a></li>
					<li><a id="menu-contact" href="/site/contact" title="Contact me" class="menu-item <?= $_GET['method'] === 'contact' ? 'selected' : '' ?>">Contact</a></li>
<?php
if ($CurrentUser->isLoggedIn()) {
	if ($GLOBALS['app']->userIsAuthorized('admin', 'index')) {
?>
					<li><a id="menu-admin" href="/admin" title="Administer Site" class="menu-item <?= $_GET['controller'] === 'admin' || $_GET['method'] === 'admin' ? 'selected' : '' ?>">Admin</a></li>
<?php
	}
?>
					<li><a href="/account/logout" title="Log Out" id="menu-logout" class="menu-item">Log Out</a></li>
<?php
}
?>
				</ul>
			</div>
