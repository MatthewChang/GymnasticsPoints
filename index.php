<head>
<link rel="stylesheet" href="reset.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="format.css" type="text/css" />

<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/iosOverlay.css">
<link rel="stylesheet" href="css/prettify.css">
<link rel="stylesheet" href="css/jquery-ui.css">

<script type="text/javascript" src="jquery-1.11.3.min.js"></script>

<script src="js/iosOverlay.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/spin.min.js"></script>
<script src="js/prettify.js"></script>
<script src="js/custom.js"></script>

<script type="text/javascript">
function login() {
	var loading = iosOverlay({
		text: "Loading",
		duration: 1e5,
		icon: "img/ring.gif"
	});	
	$.ajax({
		url: "signin.php",
		method: "POST",
		data: {user: $("#user_input").val()}
	}).done(function(msg) {
		loading.hide();
		if(msg === "1") {			
			iosOverlay({
				text: "Success!",
				duration: 1.5e3,
				icon: "img/check.png"
			});	
		} else {
			iosOverlay({
				text: "Error",
				duration: 1.5e3,
				icon: "img/cross.png"
			});	
			
		}
		get_signed_in();
	});
}

function get_signed_in() {
	var date = new Date();
	$.ajax({
		url: "signed_in.php?time="+date.getTime()
	}).done(function(msg) {
		$("#signed_in").html(msg);
	});
}


var availableTags = [
	"ActionScript",
	"AppleScript",
	"Asp",
	"BASIC",
	"C",
	"C++",
	"Clojure",
	"COBOL",
	"ColdFusion",
	"Erlang",
	"Fortran",
	"Groovy",
	"Haskell",
	"Java",
	"JavaScript",
	"Lisp",
	"Perl",
	"PHP",
	"Python",
	"Ruby",
	"Scala",
	"Scheme"
];


$( document ).ready(function() {
	get_signed_in();
	setInterval(get_signed_in,5000);
	$( "#nameinput" ).autocomplete({
		source: availableTags
	});
});
</script>


</head>
<body>
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
//echo '<div id="lightbox"><p>message</p></div>';
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
			echo '<div class="late">You are now late.</div>';
}
echo'
<div id="form"><input id="nameinput"><br>
<select id="user_input">';
foreach($not_signed_in as $id) {
	echo '<option value="'.$people[$id].'">'.$people[$id].'</option>';
}
/*echo '
  <option value="Chrome">
  <option value="Firefox">
  <option value="Internet Explorer">
  <option value="Opera">
  <option value="Safari">';*/
  echo '</select>';

/*$not_signed_in = array_diff(array_keys($people),$signed_in);
echo '<form action="#"><select name="user" id="user_id">';
foreach($not_signed_in as $id) {
	echo '<option  value="'.$id.'">'.$people[$id].'</option>';
}*/
echo ' </select><br><button onclick="login()">Submit</button></div>';
//echo '<button id="loading">Loading Spinner</button>      <button id="checkMark"">Success</button>      <button id="cross"">Error</button>';

#echo '</form>';
echo '<p class="title3">Signed In</p>';
echo '<div id="signed_in"></div>';
/*for($i = 0; $i < sizeof($signed_in); $i++) {
	echo $people[$signed_in[$i]].' '.$times[$i];	
	echo '<br>';
}*/
?>
  
</body>
