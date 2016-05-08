				<div class="content">
					<h1>Code Editor</h1>
					<div class="content-body">
<?php if ($DirectoryPath !== '.') { ?>
						<p><div class="label">Current Directory:</div><?= $DirectoryPath ?></p>
<?php } ?>
						<h2>Directories</h2>
						<ul>
<?php
if (count($Directory->Directories) === 0) {
	echo '							<li>No directories found.</li>';
} else {
	foreach ($Directory->Directories as $dir) {
		echo "							<li><a href=\"$PathPrefix$dir\">$dir</a></li>";
	}
}
?>
						</ul>
						<h2>Files</h2>
						<ul>
<?php
if (count($Directory->Files) === 0) {
	echo '							<li>No files found.</li>';
} else {
	foreach ($Directory->Files as $file) {
		echo "							<li><a href=\"$PathPrefix$file\">$file</a></li>";
	}
}
?>
						</ul>
					</div>
				</div>
