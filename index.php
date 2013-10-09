<?php 

// Utility functions
function ListPrices() {
	$outtxt = "";
	for($i=1;$i<21;$i++){
		$outtxt .= "<option value='{$i}'>${$i}</option>\n";	
	}
	return $outtxt;
}

// Assembling the movie list. For now we'll use a plain old list. 
// In the future this will pull from a database table (which will be created by a different page).

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


?>

<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="reset.css" />
<link rel="stylesheet" href="main.css" />
<meta charset="utf-8">
<title>MovieDraft.Net Sign-Up Sheet</title>
</head>

<body>

<!-- Collect the actual data -->
<div id="md_entry_wrap">
    <form id="md_entry" name="md_entry" action="" method="POST">
        <!-- we are currently performing no sanity checks on names -->
        <input type="text" name="md_entry_name" id="md_entry_name" value="" size="20"><br>
    	<?php 
		$j = 0;
		foreach($movies as $movie) {
			echo "<div id='md_entry_item_wrap_$j' class='md_entry_item_wrap'><select id='md_entry_item_$j' name='md_entry_item_$j' value=''>";
			echo ListPrices();
			echo "</select></div>";
		}
		?>
        <input type="submit" id="md_entry_submit" name="md_entry_submit" value="Submit"><br>
    </form>
</div>

</body>
</html>

<?php

?>