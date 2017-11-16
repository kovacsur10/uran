<?php

function getFormattedDate(){
	return substr(date("Y-m-d H:i:sO"), 0, -2);
}

$DEBUG = true;

date_default_timezone_set('CET');
print "The script has started!\n";

// PRE PHASE
$db_name = "";
$username = "";
$password = "";
$connStringUran = "host=localhost port=5432 dbname=".$db_name." user=".$username." password=".$password;
$dbUran = pg_connect($connStringUran) or die("Could not connect to the database (".$db_name.")!");

// QUERIES
pg_prepare($dbUran, "remove_unusable_free_pages", "DELETE FROM ecnet_free_pages WHERE ((valid_until < $1) OR (pages_left <= 0));") or die("Sysadmin query prepare error (log_close)!");

// MAIN LOGIC
pg_execute($dbUran, "remove_unusable_free_pages", [getFormattedDate()]) or die("Could not remove the unusable free printing pages!");

// POST PHASE
pg_close($dbUran) or die("Could not close the database connection (".$db_name.")!");

print "The script has ended!\n";

?>