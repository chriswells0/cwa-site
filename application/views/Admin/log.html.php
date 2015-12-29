				<div class="content">
					<h1>Log Viewer</h1>
					<div class="content-body">
						<form id="log-viewer" action="/admin/log" method="post" data-autoinit="false">
							<p>
								<div class="label">Current Log Level:</div><?= $LogLevel ?>
								<br />
								<div class="label">Log Path:</div><?= $FilePath ?>
							</p>
							<fieldset>
								<legend>Filter Log Entries</legend>
								<div class="form-field">
									<label for="log-level">Show Level</label>
									<select id="log-level" autofocus>
										<option value="TRACE">Trace</option>
										<option value="DEBUG">Debug</option>
										<option value="INFO">Info</option>
										<option value="WARN">Warn</option>
										<option value="ERROR">Error</option>
										<option value="FATAL">Fatal</option>
									</select>
									<select id="log-level-criteria">
										<option value="MINIMUM">or higher</option>
										<option value="ONLY">only</option>
									</select>
									<select id="log-level-php">
										<option value="YES">show PHP errors</option>
										<option value="NO">hide PHP errors</option>
									</select>
									<select id="stack-traces">
										<option value="YES">show stack traces</option>
										<option value="NO">hide stack traces</option>
									</select>
								</div>
								<div class="buttons">
									<button id="filter" type="submit">Apply Filter</button>
									<button id="reset" type="reset">Reset</button>
								</div>
							</fieldset>
							<textarea id="file-contents" wrap="off" readonly="true" placeholder="No results found."><?= $FileContents ?></textarea>
							<div class="buttons">
								<button id="delete" name="delete" type="submit">Delete Log</button>
								<button id="cancel" type="button">Cancel</button>
							</div>
						</form>
					</div>
				</div>
<script>
var logLines = $("#file-contents").val().split("\n"); // Retain the original list so nothing is lost while filtering. -- cwells

// Remove all log levels lower than the current setting. -- cwells
$("option[value='<?= $LogLevel ?>']").prevAll().remove();

$("#filter").on("click", function () {
	var filteredLines = [],
		selectedLevel = $("#log-level").val(),
		levels = [selectedLevel],
		showStackTraces = ($("#stack-traces").val() === "YES"),
		regExpStacks = new RegExp(/^(Stack|#[0-9])/),
		regExpLevels,
		i;
	$("#file-contents").text("Filtering...");

	if ($("#log-level-criteria").val() === "MINIMUM") { // Append all levels above the selected. -- cwells
		$("option[value='" + selectedLevel + "']").nextAll().each(function (index, element) {
			levels.push($(element).val());
		});
	}

	if ($("#log-level-php").val() === "YES") {
		levels.push("PHP");
	}

	regExpLevels = new RegExp("(" + levels.join("|") + ")");
	for (i = 0; i < logLines.length; i++) {
		if (regExpLevels.test(logLines[i]) || (showStackTraces && regExpStacks.test(logLines[i]))) {
			filteredLines.push(logLines[i]);
		}
	}

	$("#file-contents").text(filteredLines.join("\n"));
	return false;
});

$("#reset").on("click", function () {
	$("#file-contents").text("Loading...");
	$("#file-contents").text(logLines.join("\n"));
});

$("#delete").on("click", function () {
	return confirm("Are you sure you want to delete the log file?\n\nThis action cannot be undone!");
});

$("#cancel").on("click", function () {
	history.back();
	return false;
});
</script>
