<?php
require 'php/upgradephp-19/upgrade.php';
$pw = "bigredwill";
date_default_timezone_set('America/New_York');
$mysqli = new mysqli("sql.mit.edu", "m_chang", $pw, "m_chang+gymnastics");
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$people = [];
if ($result = $mysqli->query("select p_id, name from people order by name;")) {
	while ($row = $result->fetch_assoc()) {
		$people[$row["p_id"]] = $row["name"];
	}
	$result->free();
}
$entries = [];
if ($result = $mysqli->query('select entry_key, p_id, points, time from points where time >= curdate() order by time DESC;')) {
	while ($row = $result->fetch_assoc()) {
		$entry = [];
		$entry["name"] = $people[$row["p_id"]];
		$entry["key"] = $row["entry_key"];
		$entry["time"] = $row["time"];
		$entry["points"] = $row["points"];
		array_push($entries,$entry);
	}
	$result->free();
}

echo json_encode($entries);

?>
