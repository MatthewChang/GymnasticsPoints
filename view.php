<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 15px;
}
</style>

<?php
/*
gets orderd list of point totals
select people.name, sum(points.points) as total from points inner join people on people.p_id = points.p_id group by points.p_id order by total DESC;
*/
$pw = "bigredwill";
echo "<h1>View Points</h1>";
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
//print_r($people);

if(array_key_exists("user",$_GET)) {
	echo '<title>Gymnastics Points View</title>';
	$user = $_GET["user"];
	$query = 'select sum(points) as total from points where p_id = '.$user.';';
	//echo $query;
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			echo $people[$user]." - Total Points: ".$row["total"].'<br>';
		}
		$result->free();
	}
	echo '<table><tr><td>Type</td><td>Points</td><td>Time</td></tr>';
	$query = 'select * from points where p_id = '.$user.';';
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			echo '<tr><td>'.$row["type"].'</td>'.'<td>'.$row["points"].'</td>'.'<td>'.$row["time"].'</td></tr>';
		}
		$result->free();
	}
	echo '</table>';
} else {

	echo '<form action="view.php" method="GET"><select name="user">';
	foreach(array_keys($people) as $id) {
		echo '<option value="'.$id.'">'.$people[$id].'</option>';
	}
	echo ' </select><button type="submit">Submit</button>';
	echo '</form>';
}

?>
