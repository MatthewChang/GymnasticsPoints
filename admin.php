<?php
$pw = "bigredwill";
if(array_key_exists("adminpw",$_POST) and $_POST["adminpw"] == $pw){
	
	echo "<h1>Change Points</h1>";
	$mysqli = new mysqli("127.0.0.1", "root", "", "db");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	if(array_key_exists("user",$_POST)) {
		$user = $_POST["user"];
		$query = 'insert into points values('.$user.',2,'.$_POST["pc"].',NOW());';
		$result = $mysqli->query($query);
		if($result == 1) {
			echo "<h3>Updated Successfully</h3>";
		} else {
			echo "<h3>Updated Failed</h3>";
		}
	}
	
	if(array_key_exists("new_name",$_POST)) {
		$name = $_POST["new_name"];
		$query = 'insert into people (name) values("'.$name.'")';
		$result = $mysqli->query($query);
		if($result == 1) {
			echo "<h3>Added Successfully</h3>";
		} else {
			echo "<h3>Add Failed</h3>";
		}
	}
	
	
	$people = [];
	if ($result = $mysqli->query("select p_id, name from people;")) {
		while ($row = $result->fetch_assoc()) {
			$people[$row["p_id"]] = $row["name"];
		}
		$result->free();
	}
	
	echo '<form action="admin.php" method="POST"><select name="user">';
	foreach(array_keys($people) as $id) {
		echo '<option value="'.$id.'">'.$people[$id].'</option>';
	}
	
	echo '</select><br>Point Change: <input name="pc">';
	echo '<br>PW:<input name="adminpw" type="password"> <button type="submit">Submit</button>';
	echo '</form>';
	
	echo "<h1>Add Person</h1>";
	echo '<form action="admin.php" method="POST">';	
	echo 'Name: <input name="new_name">';
	echo '<br>PW:<input name="adminpw" type="password"> <button type="submit">Submit</button>';
	echo '</form>';
	
} else {
	echo '<form action="admin.php" method="POST"><input name="adminpw" type="password"> <button type="submit">Submit</button></form>';
}
?>
