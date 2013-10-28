<?php
require('sql.php');  // We're storing the SQL login info externally just for an added layer of obfuscation.
$error = "";
$success = "";

// Fire up the SQL database connection
@$sc = new mysqli($sql_host, $sql_user, $sql_pass, $sql_db); // Create a new connection
if(@$sc->connect_errno) {
	$error .= "Could not connect to database! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
}

// Process the POST data before the main SQL get; that way if there's an update it's reflected in the later request.
if($_POST) {
	$movies = array();
	$lastkey = 1;
	// Iterate through _POST and pick out the movies and bids. 
	// This is going to break if the field names don't look like movieXXX... and bidXXX...,
	// so if you add additional fields, don't break that naming scheme OR fix this code.
	foreach($_POST as $key=>$value) {
		if(substr($key,0,5) == "movie") {
			$lastkey = $id = intval(substr($key,5));
			$movies[$id]['name'] = $value;
		} elseif(substr($key,0,3) == "bid") {
			$lastkey = $id = intval(substr($key,3));
			$movies[$id]['bid'] = $value ? $value : 0;
		}
	}
	// Why not use sizeof() in this for loop? Because of the way MySQL handles auto-incremented primary keys.
	// If I delete a row from a MySQL table with an auto-incremented primary key - like we're using - it remembers
	// where it was in the increment list, and doesn't reuse the key. In this case, our key is the 'id' column.
	// (Because it auto-increments we don't have to set it manually.) If, say, we have IDs 1-4 in the table, and
	// we delete ID 4, when we add another row, we'll have IDs 1, 2, 3, and 5. But if we put those into a PHP array,
	// sizeof(the array) will be 4 (because there are only 4 IDs), so if we use sizeof() to determine what ID to stop at,
	// we'll miss ID 5. Instead, as we iterated through _POST above, we kept assigning $lastkey to the current ID.
	// That way it ends up set to the last ID in the set, and our for loop can go all the way through.
	for($i=1;$i<=$lastkey;$i++) {
		if(isset($movies[$i]) && $movies[$i]['name'] != "") { // We have to see if the array has a value here, or it'll throw errors
			// What's 'stamp'? It's the timestamp of the last time the row was modified, set as seconds since epoch.
			// Why do we need it? Because if we run an UPDATE query over a table row, but nothing's changed, 
			// SQL will return 0 rows affected (even if the query matches the row!). It's cheaper to update
			// the timestamp with each request than to run a third query to look for the row, update it if it's there,
			// and insert a new row if it's not, and a timestamp in seconds since epoch guarantees that something has changed.
			$sqlu = "UPDATE fall13titles SET name='" . $movies[$i]['name'] . "',bid='" . $movies[$i]['bid'] . "',stamp=" . time() . " where id=" . $i;
			$sqli = "INSERT INTO fall13titles (name, bid, stamp) VALUES ('" . $movies[$i]['name'] . "','" . $movies[$i]['bid'] . "'," . time() . ")";
			$sc->query($sqlu);
			$sql = "";
			if($sc->affected_rows == 0) { // For some reason, mysqli implements this on the mysqli object instead of a return object
				$sc->query($sqli);
//				$success .= $sqli . "\n";
			} else {
//				$success .= $sqlu . "\n";
			}
		}
	}
}

$next = 1;
// Get all the data from the fall13titles table, which is where we're storing the movie data (but not the bid data).
if(@!$res = $sc->query("SELECT * FROM fall13titles ORDER BY id ASC")) { // Get all the rows, order by ID so they're chronological
	$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
}




?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Movie Draft Administration</title>
<link rel="stylesheet" href="main.css" />
<script type="text/javascript" src="manip.js"></script>

</head>

<body>
<?php //if(isset($movies)) { print_r($movies); } if(isset($lastkey)) echo $lastkey;?>
<?php if ($error != "") { ?>
<div id="md_errors">
<?php echo preg_replace('/\n/','<br>\n',$error); ?>
</div>
<?php } ?>
<?php if ($success != "") { ?>
<div id="md_success">
<?php echo preg_replace('/\n/',"<br>\n",$success); ?>
</div>
<?php } ?>
<h3>Movie Draft Administration</h3>
<div id="entries">
<form id="md_entries" name="md_entries" method="POST" action="">
<table name="md_entry_item_table" id="md_entry_item_table">
<tr><th>Movie</th><th>Bid</th></tr>
<?php
if($res) {
	while($row = $res->fetch_assoc()) { // Notice how we're operating on the $res return object rather than the $sc mysqli object
		echo "<tr>\n\t<td>";
		echo "\n\t\t<input type='text' name='movie" . $row['id'] . "' id='movie" . $row['id'] . "' value='" . $row['name'] . "'>";
		echo "\n\t</td>\n<td>";
		echo "\n\t\t<input type='text' name='bid" . $row['id'] . "' id='bid" . $row['id'] . "' value='" . $row['bid'] . "'>";
		echo "\n\t</td>\n</tr>\n";
		$next = 1 + $row['id'];

	}
}

// We set $next equal to 1 way back at the beginning, so even if there are no rows in the table, this will still
// give us a meaningful ID value. We COULD just call this "movienew" and "bidnew", since at this point we're only
// allowing one new movie per submit, but in the future we may expand this to allow Javascript to add multiple new
// rows per submit, so setting up for that is a good idea.
?>
<tr>
	<td>
    	<input type="text" name="movie<?php echo $next; ?>" id="movie<?php echo $next; ?>">
    </td>
    <td>
    	<input type="text" name="bid<?php echo $next; ?>" id="bid<?php echo $next; ?>">
    </td>
</tr>
</table>
<input type="submit" name="submit" value="Submit">
</form>
</div>
</body>
</html>
<?php
// Wait, we don't have a way to delete movies without directly accessing the MySQL table!
// That's right. Not implemented yet. We'll get there.

@$sc->close();
?>