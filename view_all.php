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
if ($result = $mysqli->query("select p_id, name from people;")) {
	while ($row = $result->fetch_assoc()) {
		$people[$row["p_id"]] = $row["name"];
	}
	$result->free();
}
//print_r($people);

echo '<title>Gymnastics Points View</title>';
$query = 'select people.name, sum(points.points) as total from points inner join people on people.p_id = points.p_id group by points.p_id order by total DESC';

echo '<table><tr><td>Name</td><td>Points</td><td>Time</td></tr>';
if ($result = $mysqli->query($query)) {
	while ($row = $result->fetch_assoc()) {
		echo '<tr><td>'.$row["name"].'</td>'.'<td>'.$row["total"].'</td>'.'<td>'."uh".'</td></tr>';
	}
	$result->free();
}
echo '</table>';


?>
