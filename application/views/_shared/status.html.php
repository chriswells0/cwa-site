<?php
if ($this->getStatusMessage() !== '') {
	echo '<div class="content status-message status-' . $this->getStatusCode()
		. ($this->getStatusCode() === 200 ? '' : ' error')
		. '">' . $this->getStatusMessage() . "</div>\n";
}
?>