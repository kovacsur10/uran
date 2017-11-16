<?php

function getFormattedDate(){
	return substr(date("Y-m-d H:i:sO"), 0, -2);
}

$DEBUG = true;
$COLLEGIST_DYN_FILE_NAME = "dyn.collegist.data";

date_default_timezone_set('CET');
print "The script has started!\n";

// PRE PHASE
$connStringUran = "host=localhost port=5432 dbname= user= password=";
$connStringSysadmin = "host=localhost port=5432 dbname= user= password=";
$dbUran = pg_connect($connStringUran) or die("Could not connect to the database (uran)!");
$dbSysadmin = pg_connect($connStringSysadmin) or die("Could not connect to the database (sysadmin)!");

// QUERIES
$selectValidMACs = "SELECT DISTINCT ecnet_mac_addresses.mac_address, ecnet_user_data.user_id FROM ecnet_mac_addresses INNER JOIN ecnet_user_data ON ecnet_mac_addresses.user_id=ecnet_user_data.user_id WHERE ecnet_user_data.valid_time >= LOCALTIMESTAMP;";
pg_prepare($dbSysadmin, "insert_all_valid_addresses", "INSERT INTO ethernet (ip) VALUES ($1);") or die("Sysadmin query prepare error (insert_all_valid_addresses)!"); //only for table creation
pg_prepare($dbSysadmin, "get_next_free_ip", "SELECT ip FROM ethernet WHERE mac IS NULL ORDER BY ip ASC;") or die("Sysadmin query prepare error (get_next_free_ip)!");
pg_prepare($dbSysadmin, "insert_mac_into_database", "UPDATE ethernet SET mac=$1 WHERE ip LIKE $2;") or die("Sysadmin query prepare error (insert_mac_into_database)!");
pg_prepare($dbSysadmin, "get_mac_addresses", "SELECT DISTINCT mac FROM ethernet WHERE mac IS NOT NULL;") or die("Sysadmin query prepare error (get_mac_addresses)!");
pg_prepare($dbSysadmin, "remove_old_mac", "UPDATE ethernet SET mac=NULL WHERE mac LIKE $1;") or die("Sysadmin query prepare error (remove_old_mac)!");
pg_prepare($dbSysadmin, "get_all_data", "SELECT ip, mac FROM ethernet WHERE mac IS NOT NULL ORDER BY ip ASC;") or die("Sysadmin query prepare error (get_all_data)!");

pg_prepare($dbSysadmin, "log_open", "INSERT INTO ethernet_log (userid, ip, mac, from_date) VALUES ($1, $2, $3, $4);") or die("Sysadmin query prepare error (log_open)!");
pg_prepare($dbSysadmin, "log_close", "UPDATE ethernet_log SET to_date=$1 WHERE mac LIKE $2 AND to_date IS NULL;") or die("Sysadmin query prepare error (log_close)!");

// MAIN LOGIC

$result = pg_query($dbUran, $selectValidMACs) or die("Querying the database failed! (Uran)");
$uranFetchedData = pg_fetch_all($result);
$macAddresses = [];
if(is_array($uranFetchedData)){
	foreach($uranFetchedData as $data){
		$macAddresses[] = $data['mac_address'];
	}
}
if(count($macAddresses) > 252){
	print "ERROR: NOT ENOUGH IP ADDRESSES ARE LEFT!\n";
	exit;
}else{
	print "There are enough addresses, so the processing starts now!\n";
}

$result = pg_execute($dbSysadmin, "get_mac_addresses", []) or die("Querying the database failed (sysadmin MACs)!");
$alreadyAddressesComplex = pg_fetch_all($result);
$alreadyAddresses = [];
//remove old MAC addresses
if(is_array($alreadyAddressesComplex)){
	foreach($alreadyAddressesComplex as $address){
		$mac = $address['mac'];
		if(!in_array($mac, $macAddresses)){ //remove old addresses
			pg_execute($dbSysadmin, "remove_old_mac", [$mac]) or die("Old MAC address cannot be removed!");
			pg_execute($dbSysadmin, "log_close", [getFormattedDate() , $mac]) or die("Could not write the log closing!");
			if($DEBUG){
				print "REMOVED FROM DATABASE: ".$mac."\n";
			}
		}else{
			$alreadyAddresses[] = $mac;
		}
	}
}
//add or keep MAC addresses
if(is_array($uranFetchedData)){
	foreach($uranFetchedData as $data){
		$mac = $data['mac_address'];
		$userId = $data['user_id'];
		if(!in_array($mac, $alreadyAddresses)){ //new addresses should be added to the database
			$nextResult = pg_execute($dbSysadmin, "get_next_free_ip", []);
			$nextIp = pg_fetch_result($nextResult, 0, 0);
			pg_execute($dbSysadmin, "insert_mac_into_database", [$mac, $nextIp]) or die("New MAC address cannot be inserted!");
			pg_execute($dbSysadmin, "log_open", [$userId, $nextIp, $mac, getFormattedDate()]) or die("Could not write the log opening!");
			if($DEBUG){
				print "INSERTED TO DATABASE: ".$mac."\n";
			}
		}
	}
}

//generate the dyn.collegist table
$result = pg_execute($dbSysadmin, "get_all_data", []) or die("Querying the database failed (sysadmin all data)!");
$allData = pg_fetch_all($result);
$dynFile = fopen($COLLEGIST_DYN_FILE_NAME, "w");
if(is_array($allData)){
	foreach($allData as $row){
		$header = "host collegist.".$row['ip']."{\n";
		$dataPart1 = "    hardware ethernet ".$row['mac'].";\n";
		$dataPart2 = "    fixed-address ".$row['ip'].";\n";
		$footer = "}\n\n";
		fwrite($dynFile, $header.$dataPart1.$dataPart2.$footer);
	}
}
fclose($dynFile);

///*// creating the available ip addresses for ethernet
//for($i = 1; $i < 253; $i++){
//	if($i !== 213){ //dormitory address
//		pg_execute($dbSysadmin, "insert_all_valid_addresses", array("157.181.120.".$i)) or die("Sysadmin query execution error!");
//	}
//}*/

// POST PHASE
pg_close($dbUran) or die("Could not close the database connection (uran)!");
pg_close($dbSysadmin) or die("Could not close the database connection (sysadmin)!");

print "The script has ended!\n";

?>