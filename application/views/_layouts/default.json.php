<?php
$data = $this->getData();
array_walk_recursive($data, function (&$value, $key) {
	if (is_object($value) && method_exists($value, 'toArray')) {
		$value = $value->toArray(true);
	}
});
?>
{
	"statusCode": <?= $this->getStatusCode() ?>,
	"statusMessage": "<?= $this->getStatusMessage() ?>",
	"data": <?= json_encode($data) ?>

}