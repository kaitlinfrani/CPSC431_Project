<!DOCTYPE html>
<html>
<head>
	<title>Add Provider Form</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
		$( function() {
			$( "#datepicker" ).datepicker();
		});
	</script>
</head>
<body>
	<header>
		<h1>Add Provider Form</h1>
	</header>
	<form method="post" action="process_add_provider.php">
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" required>

		<label for="occupation">Occupation:</label>
		<input type="text" id="occupation" name="occupation" required>

		<label for="zipcode">Zip Code:</label>
		<input type="text" id="zipcode" name="zipcode" required>

		<label for="food_preference">Food Preference:</label>
		<input type="text" id="food_preference" name="food_preference" required>

		<label for="availability">Availability:</label>
		<input type="text" id="datepicker" name="date" required>
        <input type="time" id="timepicker" name="time"
        min="00:00" max="24:00" required>
        <br><br>
		<input type="submit" value="Add Provider">
	</form>
</body>
</html>
