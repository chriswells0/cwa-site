<?php
require_once 'views/_shared/status.html.php';
?>
				<div class="content">
					<h1>Code Editor</h1>
					<div class="content-body">
						<form action="/admin/code" method="post">
<?php if ($ReadOnly) { ?>
							<h2>Viewing: <?= $FilePath ?></h2>
							<p class="error">This file is not writable by the current user.</p>
<?php } else { ?>
							<h2>Editing: <?= $FilePath ?></h2>
<?php } ?>
							<input type="hidden" name="file-path" value="<?= $FilePath ?>" />
							<textarea id="file-contents" name="file-contents" wrap="off" <?= $ReadOnly ? 'readonly="true"' : '' ?> placeholder="This file is empty." autofocus required><?= $FileContents ?></textarea>
<?php if (!$ReadOnly) { ?>
							<div class="buttons">
								<button id="submit" type="submit">Save</button>
								<button type="button" onclick="history.back(); return false;">Cancel</button>
							</div>
<?php } ?>
						</form>
					</div>
				</div>
