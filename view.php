<?php

require('sql.php');  // We're storing the SQL login info externally just for an added layer of obfuscation.

	$sc = new mysqli($sql_host, $sql_user, $sql_pass, $sql_db); // Create a new connection
	if($sc->connect_errno) {
		$error .= "Could not connect to database! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}

	if(!$res = $sc->query("SELECT * FROM fall2013 ORDER BY id ASC")) { // Get all the rows, order by ID so they're chronological
		$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}
	

$movies = array("Carrie",	
				"Jackass",
				"Ender's Game",		
				"Thor",
				"The Hunger Games",
				"Frozen",
				"The Hobbit",
				"Anchorman 2",
				"August",
				"Paranormal Activity"
				);
sort($movies);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Current submissions</title>
<link rel="stylesheet" href="view.css" />
</head>

<body>
<table id="md_entries">
<tr><th>Participant</th>
<?php foreach($movies as $movie) { // Iterate through the movies, create header listings for the table
	echo "<th>$movie</th>";
}?>
</tr>
<?php
	while($row = $res->fetch_assoc()) { // This is how we iterate through the SQL results. (It's a MySQLi thing.)
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>"; // fetch_assoc puts the values into an associative array, keyed to the SQL column names.
		for($i=0;$i<10;$i++) { // Repeat 10 times, increasing the value of $i by 1 each time
			echo "<td";
			$mnum = "movie$i"; // movie0, movie1, etc. Yes, we can do this. Pretty cool, huh?
			if($row[$mnum] > 0) { echo " class='hasbid'"; } // If there's a nonzero bid, make the number stand out so it's easy to see
			echo ">$" . $row[$mnum];
			echo "</td>";
		}
		echo "</tr>";
	}
?>
</table>
</body>
</html>
<?php
	$sc->close();	// Make sure the SQL query is closed.
?>