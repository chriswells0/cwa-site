<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
	<head>
		<title><?= $this->getTitle() . ' :: ' . SITE_TITLE ?></title>

		<meta charset="UTF-8" />
		<meta name="author" content="<?= SITE_AUTHOR ?>" />
		<meta name="description" content="<?= $this->getDescription() ?>" />
		<meta name="robots" content="index,follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<?php
if (!is_null($this->getCanonicalURL())) {
	echo '		<link rel="canonical" href="' . $this->getCanonicalURL() . "\" />\n";
}
?>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

		<style id="antiClickjack">body { display: none !important; }</style>
		<script>
		if (self === top) {
			var antiClickjack = document.getElementById("antiClickjack");
			antiClickjack.parentNode.removeChild(antiClickjack);
		} else {
			top.location = self.location;
		}
		var analyticsID = "<?= (defined('ANALYTICS_ID') && is_null($GLOBALS['app']->getAuthorizedRoles()) ? ANALYTICS_ID : '') ?>";
		</script>

		<script type="application/ld+json">
		[{
			"@context": "http://schema.org",
			"@type": "WebSite",
			"name": "<?= SITE_TITLE ?>",
			"alternateName": "<?= SITE_DOMAIN ?>",
			"url": "<?= PROTOCOL_HOST_PORT ?>"
		},
		{
			"@context": "http://schema.org",
			"@type": "Person",
			"name": "<?= SITE_AUTHOR ?>",
			"url": "<?= PROTOCOL_HOST_PORT ?>",
			"sameAs": [
				/* Add your social media URLs here. */
			]
		}]
		</script>
	</head>
	<body>
		<div id="wrapper">
<?php
	require_once 'views/_shared/header.php';
	require_once 'views/_shared/menu.php';
?>
			<div id="content-wrapper">
				<link rel="stylesheet" href="/styles/main.css" media="screen, print" />
				<script src="//code.jquery.com/jquery.min.js"></script>
				<script src="/scripts/cwa.js"></script>
				<script src="/scripts/main.js"></script>
<?php
	require_once $this->pathToPartial;
	require_once 'views/_shared/footer.php';
?>
			</div>
<?php
?>
		</div>
	</body>
</html>
