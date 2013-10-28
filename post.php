<?php

/* FORM PROCESSING */

$error = ""; // Setting up to throw errors.
$success = ""; // Setting up to relay successes.

// First, set up the connection parameters.
// TO DO: Get these values!
require('sql.php'); // We're storing the SQL login info externally just for an added layer of obfuscation.
		
// The MySQLi functions are discussed here: http://www.php.net/manual/en/book.mysqli.php
@$sc = new mysqli($sql_host, $sql_user, $sql_pass, $sql_db); // Create a new connection
if($sc->connect_errno) {
	$error .= "Could not connect to database! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
}


// There are two ways to submit an HTML form, POST and GET. POST sends the data behind-the-scenes,
// GET sends the data through the URL. (If you see a URL that looks like
// http://www.example.com/index.php?id=12345&text=Hello%20world!
// that's a GET request; everything after the ? is GET data.)
// GET is inherently insecure (because anyone can just load the page with corrupt data in the URL),
// so we prefer POST unless we're not storing the data in a dataset table or displaying it directly.

if($_POST) { // If we haven't received POST data, this variable is false. The GET equivalent is $_GET.
	// Iterate through _POST and pick out the movies and bids. 
	// This is going to break if the field names don't look like movieXXX...,
	// so if you add additional fields, don't break that naming scheme OR fix this code.
	foreach($_POST as $key=>$value) {
		if(substr($key,0,5) == "movie") {
		// intval() makes sure that we're only getting a number.
		// An interesting quirk of intval() is that anything that
		// doesn't have a number at the very beginning returns 0.
		// This seems weird when you first look at it, but it's 
		// the expected behavior if you think about it; it's not just
		// pulling the first integer it sees, it's checking the 
		// integer value of the contents of the variable. If the
		// variable doesn't contain an integer, its value is 0.
		// This means that if these values are invalid - they aren't 
		// set, or are text - we're getting a $0 bid out of them. 
			$id = intval(substr($key,5));
			$movies[$id]['bid'] = $value;
		} elseif ($key == 'md_entry_name') {
			$name = $value;
		} elseif ($key == 'lastid') {
			$lastkey = $value;
		}
	}
	// We can't use intval() to sanitize a string, so we have to do a little extra work.
	// Using protection. Stripping out everything after a semicolon protects against SQL injection.
	$name = preg_replace('/;.*$/','',$name);
	// htmlentities() changes everything that has an HTML entity code to that code. Use html_entity_decode() to get back.
	// intval() and htmlentities() insulate us from insertion attacks. Someone could POST to this page with corrupt
	// or malicious values for the POST variables, or put the data straight into the name field and submit it. 
	$name = htmlentities($name);
	if($name == "") {
		$error .= "No name submitted!";
	} else {
		// Now our data should be safe to store in the database table.
		
		// Database table structure is as follows:
		// Columns are id, name, movie0 through movie9.
		// Rows are per user. 
		// Right now, we're going to set the data and then retrieve it so that we know it's stored.
		// We're not doing a sanity check on names right now, but we could if we wanted to.
		// This means that two people can have the same name. Not optimal, but we'll live.
		$sql = "INSERT INTO fall2013 VALUES ('','$name'";
		for($i=1;$i<=$lastkey;$i++) {
			if(isset($movies[$i]) && $movies[$i]['bid'] == 1) {
				$sql .= ",'1'";
			} else {
				$sql .= ",'0'";
			}
		}
		$sql .= ")";
		//$success .= $sql; // Diagnostic; only uncomment if you need to see the SQL query string
		$sc->query($sql);		
		// We don't have a way to tell what the ID is, and names aren't unique, but we can be clever with SQL.
		// Get everything from the fall2013 table. Sort the results by the "id" column (which auto-increments
		// with every new row inserted), and sort them backwards so the largest ID comes in first. Then only
		// pull one record.
		if($res = $sc->query("SELECT * FROM fall2013 ORDER BY id DESC LIMIT 1")) {
			$row = $res->fetch_assoc();
			$success .= "Successfully submitted your bids, {$row['name']}!";
		} else {
			$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
		}
	}
}



if(@!$gs = $sc->query("SELECT * FROM fall13titles ORDER BY id ASC")) { // Get all the rows, order by ID so they're chronological
	$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
}


?>