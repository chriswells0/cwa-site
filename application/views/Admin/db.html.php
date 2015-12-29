<?php
require_once 'views/_shared/status.html.php';
?>
				<div class="content">
					<a href="/admin/db/backup" title="Create a database backup" class="heading-addon">Create Backup</a>
					<h1>Database Administrator</h1>
					<div class="content-body">
						<form id="db-admin" action="/admin/db" method="post" data-autoinit="false">
<?php
if (!empty($Tables)) {
?>
							<p>
								<div class="label">Tables:</div><?= implode(', ', $Tables) ?>
							</p>
<?php
}

if (!empty($History)) {
?>
							<p>
								<div class="label">Recent Queries:</div>
								<select id="query-history">
<?php
	foreach ($History as $historyItem) {
?>
									<option value="<?= $historyItem ?>"><?= $historyItem ?></option>
<?php
	}
?>
								</select>
							</p>
<?php
}
?>
							<textarea id="query" name="query" wrap="off" placeholder="Enter SQL statement to execute."><?= end($History) ?></textarea>
							<div class="buttons">
								<button id="execute" name="execute" type="submit">Execute</button>
								<button id="cancel" type="button">Cancel</button>
							</div>
						</form>
<?php
if (isset($RowCount)) {
?>
						<p><div class="label">Affected Rows:</div><?= $RowCount ?></p>
<?php
}

if (isset($DBError)) {
?>
						<p class="error"><div class="label">Error:</div><?= $DBError ?></p>
<?php
}

if (!empty($Result)) {
?>
						<div id="db-results">
							<table>
								<thead>
									<tr>
<?php
	$columns = array_keys($Result[0]);
	foreach ($columns as $column) {
?>
										<th><?= $column ?></th>
<?php
	}
?>
									</tr>
								</thead>
								<tbody>
<?php
	foreach ($Result as $record) {
?>
									<tr>
<?php
		foreach ($record as $column => $value) {
?>
										<td><?= $value ?></td>
<?php
		}
?>
									</tr>
<?php
	}
?>
								</tbody>
							</table>
						</div>
<?php
}
?>
					</div>
				</div>
<script>
$("#query-history > option").last().attr("selected", "selected");

$("#query-history").on("change", function () {
	$("#query").val($(this).val());
});

$("#cancel").on("click", function () {
	document.location = "/admin";
	return false;
});
</script>
