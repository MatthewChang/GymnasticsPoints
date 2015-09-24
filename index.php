<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script type="text/javascript">
function login() {
	$.ajax({
		url: "signin.php",
		method: "POST",
		data: {user: $("#user_id").val()}
	}).done(function() {
		get_signed_in();
	});
}

function get_signed_in() {
	$.ajax({
		url: "signed_in.php"
	}).done(function(msg) {
		$("#signed_in").html(msg);
	});
}
$( document ).ready(function() {
  get_signed_in();
});
</script>
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

echo '<title>Gymnastics Practice Sign In</title>';
echo "<h1>Practice Sign In</h1>";
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
			$query = 'insert into points (p_id,type,points,time) values('.$user.',"PRACTICE",2,NOW());';
			if(date('Hi') > "1730") {
				$query = 'insert into points (p_id,type,points,time) values('.$user.',"LATE",1,NOW());';
			}
			$result = $mysqli->query($query);
		}
	}
}
if(date('Hi') > "1730") {
			echo "<b>You are now late.</b>";
}

if ($result = $mysqli->query('select entry_key, p_id, time from points where time >= curdate() and (type = "LATE" or type = "PRACTICE") order by time;')) {
	while ($row = $result->fetch_assoc()) {
		array_push($signed_in,$row["p_id"]);
		array_push($keys,$row["entry_key"]);
		array_push($times,$row["time"]);
	}
	$result->free();
}


$not_signed_in = array_diff(array_keys($people),$signed_in);
echo '<form action="#"><select name="user">';
foreach($not_signed_in as $id) {
	echo '<option id="user_id" value="'.$id.'">'.$people[$id].'</option>';
}
echo ' </select><button onclick="login()">Submit</button>';
echo '</form>';
echo "<h3>Signed In</h3>";
echo '<div id="signed_in"></div>';
/*for($i = 0; $i < sizeof($signed_in); $i++) {
	echo $people[$signed_in[$i]].' '.$times[$i];	
	echo '<br>';
}*/
?>
