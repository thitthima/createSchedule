<!DOCTYPE html>
<html>
<head>
	<title>Create Schedule</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body background="background.jpg">
	<center>
		<div id="container">
			<form name='createSchedule' onSubmit="return validation()" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
				<label>Schedule Name: </label>
				<input type = "text" name = "scheduleName" id="scheduleName"/>
				<br><br>
				<label>Date : </label>
				<input oninput="checkDate()" type = "date" name = "date" id="date"/>
			</form>
		</div>
	</center>
</body>
</html>