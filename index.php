<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script type="text/javascript">
function login() {
	$.ajax({
		url: "signin.php",
		method: "POST",
		data: {user: $("#user_input").val()}
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

if(date('Hi') > "1730") {
			echo "<b>You are now late.</b>";
}
echo'
<label><input id="user_input" list="users" /></label>
<datalist id="users">';
foreach($not_signed_in as $id) {
	echo '<option value="'.$people[$id].'">';
}
/*echo '
  <option value="Chrome">
  <option value="Firefox">
  <option value="Internet Explorer">
  <option value="Opera">
  <option value="Safari">';*/
  echo '</datalist>';

/*$not_signed_in = array_diff(array_keys($people),$signed_in);
echo '<form action="#"><select name="user" id="user_id">';
foreach($not_signed_in as $id) {
	echo '<option  value="'.$id.'">'.$people[$id].'</option>';
}*/
echo ' </select><button onclick="login()">Submit</button>';
#echo '</form>';
echo "<h3>Signed In</h3>";
echo '<div id="signed_in"></div>';
/*for($i = 0; $i < sizeof($signed_in); $i++) {
	echo $people[$signed_in[$i]].' '.$times[$i];	
	echo '<br>';
}*/
?>
