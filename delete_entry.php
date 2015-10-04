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

if($pw == $_POST["pw"]) {
	$query = "delete from points where entry_key = ".$_POST["entry"].";";
	if ($result = $mysqli->query($query)) {
		echo "1";
	} else {
		echo "0: query fail";
	}
} else {	
	echo "0: pwfail";
}
?>
