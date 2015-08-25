<?php
$pw = "bigredwill";
date_default_timezone_set('America/New_York');

if((array_key_exists("pw",$_POST) and $_POST["pw"] == $pw) or (array_key_exists("pw",$_COOKIE) and $_COOKIE["pw"] == $pw)){
	setcookie("pw",$pw,time()+(24 * 3600 * 1000));
	echo "<h1>Practice Sign In</h1>";
	$mysqli = new mysqli("127.0.0.1", "root", "", "db");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	$people = [];
	if ($result = $mysqli->query("select p_id, name from people;")) {
		while ($row = $result->fetch_assoc()) {
			$people[$row["p_id"]] = $row["name"];
		}
		$result->free();
	}	
	$not_signed_in = array_keys($people);
	$signed_in = [];
	//print_r($people);
	if ($result = $mysqli->query("select p_id from points where time >= curdate() and t_id = 1;")) {
		while ($row = $result->fetch_assoc()) {
			array_push($signed_in,$row["p_id"]);
		}
		$result->free();
	}
	
	
	if(array_key_exists("user",$_POST)) {
		$user = $_POST["user"];
		if(!in_array($user,$signed_in)){
			$query = 'insert into points values('.$user.',1,2,NOW());';
			echo date('Hi');
			if(date('Hi') > "1630") {
				$query = 'insert into points values('.$user.',1,1,NOW());';
			}			
			$result = $mysqli->query($query);
			array_push($signed_in,$user);
		}
	}
	
	$not_signed_in = array_diff(array_keys($people),$signed_in);
	//print_r($not_signed_in);
	//print_r($signed_in);
	echo '<form action="index.php" method="POST"><select name="user">';
	foreach($not_signed_in as $id) {
		echo '<option value="'.$id.'">'.$people[$id].'</option>';
	}
	echo ' </select><button type="submit">Submit</button>';
	echo '</form>';
	echo "<h3>Signed In</h3>";
	foreach($signed_in as $id) {
		echo $people[$id].'<br>';
	}
	
} else {
	echo '<form action="index.php" method="POST"><input name="pw" type="password"> <button type="submit">Submit</button></form>';
}
?>
