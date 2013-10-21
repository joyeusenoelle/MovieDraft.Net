<?php

/* FORM PROCESSING */

$error = ""; // Setting up to throw errors.

// There are two ways to submit an HTML form, POST and GET. POST sends the data behind-the-scenes,
// GET sends the data through the URL. (If you see a URL that looks like
// http://www.example.com/index.php?id=12345&text=Hello%20world!
// that's a GET request; everything after the ? is GET data.)
// GET is inherently insecure (because anyone can just load the page with corrupt data in the URL),
// so we prefer POST unless we're not storing the data in a dataset table or displaying it directly.

if($_POST) { // If we haven't received POST data, this variable is false. The GET equivalent is $_GET.
	// I can get away with this right now because the list of movies is static. When we have a more
	// general page, we'll need better code here.
	$movie0 = intval($_POST['md_entry_item_0']); // intval() makes sure that we're only getting a number.
	$movie1 = intval($_POST['md_entry_item_1']); // An interesting quirk of intval() is that anything that
	$movie2 = intval($_POST['md_entry_item_2']); // doesn't have a number at the very beginning returns 0.
	$movie3 = intval($_POST['md_entry_item_3']); // This seems weird when you first look at it, but it's 
	$movie4 = intval($_POST['md_entry_item_4']); // the expected behavior if you think about it; it's not just
	$movie5 = intval($_POST['md_entry_item_5']); // pulling the first integer it sees, it's checking the 
	$movie6 = intval($_POST['md_entry_item_6']); // integer value of the contents of the variable. If the
	$movie7 = intval($_POST['md_entry_item_7']); // variable doesn't contain an integer, its value is 0.
	$movie8 = intval($_POST['md_entry_item_8']); // This means that if these values are invalid - they aren't 
	$movie9 = intval($_POST['md_entry_item_9']); // set, or are text - we're getting a $0 bid out of them. 
	$name = $_POST['md_entry_name']; 	
	// We can't use intval() to sanitize a string, so we have to do a little extra work.
	// Using protection. Stripping out everything after a semicolon protects against SQL injection.
	$name = preg_replace('/;.*$/','',$name);
	// htmlentities() changes everything that has an HTML entity code to that code. Use html_entity_decode() to get back.
	// intval() and htmlentities() insulate us from insertion attacks. Someone could POST to this page with corrupt
	// or malicious values for the POST variables, or put the data straight into the name field and submit it. 
	$name = htmlentities($name);
	
	// Now our data should be safe to store in the database table.
	// First, set up the connection parameters.
	// TO DO: Get these values!
	$sql_host = "127.0.0.1";
	$sql_user = "root";
	$sql_pass = "g3licht3n";
	$sql_db   = "moviedraft";
	
	// The MySQLi functions are discussed here: http://www.php.net/manual/en/book.mysqli.php
	$sc = new mysqli($sql_host, $sql_user, $sql_pass, $sql_db); // Create a new connection
	if($sc->connect_errno) {
		$error .= "Could not connect to database! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}
	
	// Database table structure is as follows:
	// Columns are id, name, movie0 through movie9.
	// Rows are per user. 
	// Right now, we're going to set the data and then retrieve it so that we know it's stored.
	// We're not doing a sanity check on names right now, but we could if we wanted to.
	// This means that two people can have the same name. Not optimal, but we'll live.
	if(!$sc->query("INSERT INTO fall2013 (name, movie0, movie1, movie2, movie3, movie4, movie5, movie6, movie7, movie8, movie9) VALUES ($name, $movie0, $movie1, $movie2, $movie3, $movie4, $movie5, $movie6, $movie7, $movie8, $movie9)")) {
		$error .= "Could not insert new data! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}
	
	// We don't have a way to tell what the ID is, and names aren't unique, but we can be clever with SQL.
	// Get everything from the fall2013 table. Sort the results by the "id" column (which auto-increments
	// with every new row inserted), and sort them backwards so the largest ID comes in first. Then only
	// pull one record.
	if($res = $sc->query("SELECT * FROM fall2013 ORDER BY id DESC LIMIT 1")) {
		$row = $res->fetch_assoc();
	} else {
		$error .= "Could not get data from table! Error " . $sc->connect_errno . ": " . $sc->connect_error . "<br>";
	}
	
	$sc->close();	
}



?>