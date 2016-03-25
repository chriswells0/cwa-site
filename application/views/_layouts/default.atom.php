<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom"
	  xml:lang="en"
	  xml:base="<?= PROTOCOL_HOST_PORT . \CWA\APP_ROOT ?>">

	<id><?= 'tag:' . DOMAIN . ',2015:' . $ControllerURL . ':feed/atom' ?></id>
	<link rel="self" type="<?= \CWA\Net\HTTP\HttpResponse::getContentType() ?>" href="<?= $ControllerURL ?>?format=atom" />
	<link rel="alternate" type="text/html" href="<?= $ControllerURL ?>" />
	<updated><?php
$lastUpdated = null;
$field = $Items[0]->getUpdatedFieldName();
foreach ($Items as $item) { // Use the timestamp of the most recently updated item. -- cwells
	if ($item->$field > $lastUpdated) $lastUpdated = $item->$field;
}
$lastUpdated = new DateTime($lastUpdated);
echo $lastUpdated->format(DateTime::ATOM);
?></updated>
	<title><?= SITE_NAME ?></title>
	<subtitle><?= SITE_SLOGAN ?></subtitle>
	<author>
		<name><?= SITE_AUTHOR ?></name>
		<uri><?= PROTOCOL_HOST_PORT ?></uri>
	</author>
	<rights>Copyright (c) 2014-<?= date('Y') . ', ' . SITE_AUTHOR ?></rights>

<?php
$idPrefix = 'tag:' . DOMAIN . ',2015:' . $ModelType . ':';
require_once $this->pathToPartial;
?>

</feed>
