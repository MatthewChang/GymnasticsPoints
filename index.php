<head>

<?php 
$pw = "bigredwill";
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
?>

<link rel="stylesheet" href="reset.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="format.css" type="text/css" />
<link rel="stylesheet" href="format.css" type="text/css"/>

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
var tap_count = 0;
var start_time = 0;
var date = new Date();
var pw;
function login() {
	var loading = iosOverlay({
		text: "Loading",
		duration: 1e5,
		icon: "img/ring.gif"
	});
	$.ajax({
		url: "signin.php",
		method: "POST",
		data: {user: $("#nameinput").val()}
	}).done(function(msg) {
		$("#nameinput").val("");
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
	date = new Date();
	$.ajax({
		url: "signed_in.php?time="+date.getTime()
	}).done(function(msg) {
		$("#signed_in").html(msg);
	});
}


var availableTags = [
<?php
$str = "";
foreach ($people as $id => $name) {
	$str = $str.'"'.$name.'",';
}
$str = substr($str,0,-1);
echo $str;
?>
];
function delete_entry(message,key) {
	if(confirm(message)) {
		var loading = iosOverlay({
			text: "Loading",
			duration: 1e5,
			icon: "img/ring.gif"
		});
		$.ajax({
			url: "delete_entry.php",
			method: "POST",
			data: {entry: key, pw: pw}
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
			load_admin_page();
		});
	}	
}
function load_admin_page() {
	$("#admin_page").fadeIn("slow");
	date = new Date();
	$.ajax({
		url: "recent_points.php?time="+date.getTime(),
		dataType: "json"
	}).done(function(data) {
		$("#admin_page_content").html("");
		for(var i =0; i < data.length; i++) {
			var message = 'Delete entry for '+data[i].points+' points for '+data[i].name+'?';
			$("#admin_page_content").append('<p onclick="delete_entry(\''+message+'\','+data[i].key+')">'+data[i].name+" "+data[i].time+"</p>");
		}
	});
}

function admin_login() {
	$("#admin_sign_in").fadeIn("slow");
}

function admin_pw_check() {
	pw = $("#admin_pw").val();
	
	var loading = iosOverlay({
		text: "Loading",
		duration: 1e5,
		icon: "img/ring.gif"
	});
	date = new Date();
	$.ajax({
		url: "password_check.php?time="+date.getTime(),
		method: "POST",
		data: {pw: pw}
	}).done(function(msg) {
		loading.hide();
		if(msg === "1") {
			iosOverlay({
				text: "Success!",
				duration: 1.5e3,
				icon: "img/check.png"
			});	
			load_admin_page();
		} else {
			iosOverlay({
				text: "Error",
				duration: 1.5e3,
				icon: "img/cross.png"
			});	
		}		
		$("#admin_sign_in").click();
	});
}

$( document ).ready(function() {
	get_signed_in();
	setInterval(get_signed_in,5000);
	$( "#nameinput" ).autocomplete({
		source: availableTags,
		select: function(event, ui) {
			$("#nameinput").blur();
			$(this).val(ui.item.value);
			login();			
		}
	});
	
	$("#title").click(function() {
		date = new Date();
		if(date.getTime() - start_time > 5000) {
			tap_count = 0;
			start_time = date.getTime();
		}
		tap_count++;
		
		if(tap_count >= 3) {
			admin_login();
		}
	});
	
	$("#admin_sign_in").click(function(e) {
		if($(e.target).is("#admin_sign_in")) {
			$("#admin_sign_in").hide();
		}
	})
	
	$("#admin_pw").keydown(function(event){
		if(event.keyCode == 13){
			admin_pw_check();
		}
	});
	
	$("#exit").click(function(event){
		$("#admin_page").fadeOut("slow");
	});
});
</script>

<meta name='viewport' content='user-scalable=0'>

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

date_default_timezone_set('America/New_York');
echo '<div id="admin_page"><div id="exit">Exit</div><div id="admin_page_content"></div></div>';
echo '<div id="admin_sign_in"><div><input id="admin_pw" type="password"></div></div>';
echo '<title>Gymnastics Practice Sign In</title>';
//echo '<div id="lightbox"><p>message</p></div>';
echo '<h1 id="title">Practice Sign In</h1>';


echo'<div id="form"><input id="nameinput"><br>';
//echo '</select><br><button onclick="login()">Submit</button>';
echo '</div>';
//echo '<button id="loading">Loading Spinner</button>      <button id="checkMark"">Success</button>      <button id="cross"">Error</button>';

#echo '</form>';
if(date('Hi') > "1730" or (date('N') == 6 and date('Hi') > "1600")) {
			echo '<div class="late">You are now late.</div>';
}
echo '<p class="title3">Signed In</p>';
echo '<div id="signed_in"></div>';
/*for($i = 0; $i < sizeof($signed_in); $i++) {
	echo $people[$signed_in[$i]].' '.$times[$i];	
	echo '<br>';
}*/
?>
  
</body>
