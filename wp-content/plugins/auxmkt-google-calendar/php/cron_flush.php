<?php
// Clears out the contents of the cache in order to force a rebuild. This should be
// considered a temporary fix while the Google Calendar update issues are investigated.

// Suggested run: once every day

$files = glob('/Users/Graphics/Sites/auxmkt-google-calendar/output/*');

foreach($files as $file) {
	if(is_file($file)) {
		unlink($file);
	}
}

//echo "This ran successfully";

?>