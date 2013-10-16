<?php 

/* UTILITY FUNCTIONS */
function ListPrices() {
	$outtxt = ""; // Initialize the string
	for($i=0;$i<=18;$i++){ // Start at 0; keep going as long as $i is $20 or less; add 1 to $i with each step
		$outtxt .= "<option value='$i'>$i</option>\n"; // Append to string; variable values are added automatically; \n is line break
	}
	return $outtxt; // Send string to whatever called the function
	// We're returning the string instead of echoing it directly to keep the function flexible.
	// This way, if we want to prep in advance and then use the string later, we can.
	// It's small and subtle, but a useful habit to get into.
}

/* GENERAL SETUP */

include('post.php'); // Separate major functionality gets separate files. Not necessary, but more readable.

// Assembling the movie list. For now we'll use a plain old list. 
// In the future this will pull from a database table (which will be created by a different page).

// Each title is on a different line to make the list easier to read and edit.
// We can do this because PHP considers all whitespace to be identical and 
// doesn't distinguish between, say, a space and a line break, just like HTML.

$movies = array("Carrie",	
				"Jackass Presents: Bad Grandpa",
				"Ender's Game",		
				"Thor: The Dark World",
				"The Hunger Games: Catching Fire",
				"Frozen",
				"The Hobbit: The Desolation of Smaug",
				"Anchorman 2: The Legend Continues	",
				"August: Osage County",
				"Paranormal Activity: The Marked Ones"
				);
sort($movies); // Sort them alphabetically. When we move to a database table, we can have the SQL query do this for us.

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<!-- Reset the CSS state. This makes design easier because it gets rid of many stupid browser quirks. -->
<link rel="stylesheet" href="reset.css" />
<!-- This is our main CSS file. Kept external so that if we want to just change the design, we don't have to re-upload the whole page. -->
<link rel="stylesheet" href="main.css" />

<!-- This is jQuery, a Javascript library that makes a lot of things a lot easier (see jquery.com). We load it first so our custom Javascript can take advantage of it, even though it's in a separate file. The 'min' means that it's 'minified' - short variable and function names, minimal whitespace, etc. - to make it smaller and more efficient, but WAY less readable. -->
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<!-- This is our main Javascript file. Kept external for the same reason as the CSS. -->
<script type="text/javascript" src="main.js"></script>
<!-- Note the </script> closing tags. Ideally, the HTML definition would overload the <script> tag, so we'd have two versions: one that's a singleton (<script />) and one that has content (<script></script>). But they don't. <script> is always treated as a content-bearing tag, so you have to close it explicitly even when you're just loading an external script. On the plus side, this means you can put "spare" Javascript straight into the same <script> tag and not add extra overhead - for example, if you're loading someone else's script and don't want to modify it directly. -->
<title>MovieDraft.Net Sign-Up Sheet</title>
</head>

<body>

<!-- Collect the actual data -->
<div id="md_entry_wrap">
	<!-- When a <form>'s action attribute is blank, that means it loads the same page again for processing. -->
    <form id="md_entry" name="md_entry" action="" method="POST">
        <!-- We are currently performing no sanity checks on names. -->
        <label form="md_entry" for="md_entry_name"><strong>Name:</strong></label> <input type="text" name="md_entry_name" id="md_entry_name" value="" size="20"><br>
        <br>
        <strong>Buy-ins:</strong><br>
        <div id="md_entry_item_wrap" class="md_entry_item_wrap">
        <table name="md_entry_item_table" id="md_entry_item_table">
    	<?php 
		$j = 0;
		foreach($movies as $movie) {
			echo "<tr><td><label form='md_entry' for='md_entry_item_$j'>$movie</label></td>";
			echo "<td>$<select id='md_entry_item_$j' name='md_entry_item_$j' class='md_entry_item' value=''>";
			echo ListPrices();
			echo "</select></td></tr>\n";
			$j++;
		}
		?>
		</table>
        </div><br>
        <div id="md_entry_total"><strong>Your current total: $<span id="md_entry_total_val">0</span></strong></div><br>
        <input type="submit" id="md_entry_submit" name="md_entry_submit" value="Submit your bids" disabled><br>
    </form>
</div>

</body>
</html>

<?php

?>