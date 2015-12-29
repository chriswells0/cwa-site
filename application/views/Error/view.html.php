<div class="content">
<?php
if (file_exists("views/Error/{$this->getStatusCode()}.php")) {
	require_once "views/Error/{$this->getStatusCode()}.php";
} else { // No custom page exists for this error code. -- cwells
?>
	<h1>Error: <?= $this->getStatusCode() ?></h1>
	<p>A <?= $this->getStatusCode() ?> error was encountered.</p>
<?php
}
?>
	<p>I've made a note of this issue.  If it's my fault, I'll fix it when I have time.</p>
	<p>Here are a few things you can try in the meantime:</p>
	<ol>
		<li>Verify the address to be sure there are no typos.</li>
<?php if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) { ?>
		<li>Return to the <a href="javascript:history.back()">page you came from</a>.</li>
		<li>Start over at <a href="/">my home page</a> and see where life takes you.</li>
<?php } else { ?>
		<li>Start over at <a href="/">my home page</a> and see where life takes you.</li>
		<li>Return to the <a href="javascript:history.back()">page you came from</a>.</li>
<?php } ?>
	</ol>
</div>
