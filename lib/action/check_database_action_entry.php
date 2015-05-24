<?php

/* 

	checks database configured in ttrss for notiy entry
	in table ttrss_filter_actions.

	may be used for upgrade etc.

	please execute from ttrss-root directory!

*/

set_include_path(get_include_path() . PATH_SEPARATOR . ".");
set_include_path(get_include_path() . PATH_SEPARATOR . "./include");

require_once "autoload.php";
require_once "functions.php";
#require_once "rssfuncs.php";
require_once "config.php";
require_once "sanity_check.php";
require_once "db.php";
require_once "db-prefs.php";

$result = db_query("SELECT id FROM ttrss_filter_actions WHERE name = 'notify' AND id = 100");

if (db_num_rows($result) == 0) {
	echo "Entry is missing, inserting...\n";
	db_query("INSERT INTO ttrss_filter_actions (id, name, description) VALUES (100, 'notify', 'Send notification')");
	echo "Ok\n";
} else {
	echo "Entry exists.\n";
}

?>
