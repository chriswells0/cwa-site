<?php
if (!empty($PreviousPage) || !empty($NextPage)) {
	echo '<div id="pagination">';
	if (!empty($PreviousPage)) {
		echo '<a class="page-action" rel="prev" href="' . $PreviousPage . '" title="View previous page">← Previous Page</a>';
	}
	if (!empty($NextPage)) {
		echo '<a class="page-action" rel="next" href="' . $NextPage . '" title="View next page">Next Page →</a>';
	}
	echo "</div>\n";
}
?>
