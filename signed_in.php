
<?php
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
$signed_in = [];
$keys = [];
$times = [];
if ($result = $mysqli->query('select entry_key, p_id, time from points where time >= curdate() and (type = "LATE" or type = "PRACTICE") order by time DESC;')) {
	while ($row = $result->fetch_assoc()) {
		array_push($signed_in,$row["p_id"]);
		array_push($keys,$row["entry_key"]);
		array_push($times,$row["time"]);
	}
	$result->free();
}
for($i = 0; $i < sizeof($signed_in); $i++) {
	$time = preg_replace('/\d+-\d+-\d+/i',"",$times[$i]);
	echo '<p>';
	echo $people[$signed_in[$i]].' '.$time;
	echo '</p>';
}
?>
