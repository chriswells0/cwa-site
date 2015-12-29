<?php
if (!empty($BackURL) || !empty($NextURL)) {
	echo '<div id="pagination">';
	if (!empty($BackURL)) {
		echo '<a class="page-action" rel="prev" href="' . $BackURL . '" title="View previous page">← Previous Page</a>';
	}
	if (!empty($NextURL)) {
		echo '<a class="page-action" rel="next" href="' . $NextURL . '" title="View next page">Next Page →</a>';
	}
	echo "</div>\n";
}
?>
