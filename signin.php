<?php
/*
MIT Gymnastics point entry site:
Designed to work with 2 sql tables setup as follows

points(
	entry_key INT NOT NULL AUTO_INCREMENT,
	p_id INT NOT NULL,
	type VARCHAR(255) NOT NULL,
	points INT NOT NULL,
	time DATETIME,
	PRIMARY KEY(entry_key)
);

people(
	p_id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255),
	PRIMARY KEY (p_id)
);

*/
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
$not_signed_in = array_keys($people);
$signed_in = [];
$keys = [];
$times = [];

if(array_key_exists("user",$_POST)) {
	$user = $_POST["user"];

	if ($result = $mysqli->query('select p_id, time from points where time >= curdate() and (type = "LATE" or type = "PRACTICE") and p_id = '.$user.';')) {
		if($result->num_rows == 0) {
			#echo "here2";
			$query = 'insert into points (p_id,type,points,time) values('.$user.',"PRACTICE",2,NOW());';
			#echo date('Hi');
			if(date('Hi') > "1730") {
				$query = 'insert into points (p_id,type,points,time) values('.$user.',"LATE",1,NOW());';
			}
			#echo "here3";
			$result = $mysqli->query($query);
		}
		#echo "here4";
	}
}
?>
