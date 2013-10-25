<?php
require('sql.php');  // We're storing the SQL login info externally just for an added layer of obfuscation.

if($_POST) {
	
}
	$sc = new mysqli($sql_host, $sql_user, $sql_pass, $sql_db); // Create a new connection
	if($sc->connect_errno) {
		$error .= "Could not connect to database! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}

	if(!$res = $sc->query("SELECT * FROM fall13titles ORDER BY id ASC")) { // Get all the rows, order by ID so they're chronological
		$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Movie Draft Administration</title>
</head>

<body>
</body>
</html>
<?php

?>