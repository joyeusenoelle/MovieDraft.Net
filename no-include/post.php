<?php 

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>POST functionality test</title>
</head>

<body>
<?php 
if($_POST) {
	print_r($_POST);
	$keys = array();
	foreach($_POST as $key=>$value) {
		$$key = $value;
		$keys[] = $key;
		if(substr($key,0,5) == "movie") {
			echo "<br>$key is a movie!";
		}
	}
	foreach($keys as $value) {
		echo "<br>$value => {$$value}";
	}
}
?>
<br>
<br>

<form id="post_test" name="post_test" method="POST" action="">
<input type="text" name="movie1" value="Casablanca"><br>
<input type="text" name="movie2" value="The Maltese Falcon"><br>
<input type="radio" name="movie3" value="The Last Starfighter" id="movie3_1" checked> The Last Starfighter<br>
<input type="radio" name="movie3" value="The Last Airbender" id="movie3_2"> The Last Airbender<br>
<input type="checkbox" name="movie4[]" value="Dr No" id="movie4_1"> Dr. No<br>
<input type="checkbox" name="movie4[]" value="The Spy Who Loved Me" id="movie4_2"> The Spy Who Loved Me<br>
<input type="text" name="book1" value="The Master and the Margarita"><br>
<input type="submit" value="Submit" name="Submit">
</form>
</body>
</html>