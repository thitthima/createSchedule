<?php
	require "db-handler.php";
	// script for create schedule
	session_start();
	if(isset($_GET['error']) && $_GET['error']=='schedulenametaken') {
		echo '<script type="text/javascript">alert("Hey! That schedule name is taken. Please try another one.");</script>';
	}

	if(isset($_GET['msg'])) {
		if($_GET['msg']=="success") {
			echo '<script type="text/javascript">alert("Successfully added all tasks!");</script>';
		}
	}
	
	// Create schedule
	if(isset($_POST['create-schedule-submit'])) {
		$userID = 6; //later change to $_SESSION['userID'] when login dah siap
		$schedule_name = $_POST['scheduleName'];
		$schedule_date = $_POST['date'];

		// Check if schedule name is already taken for this user
		$sql = "SELECT schedule_name FROM schedule WHERE schedule_name=? AND userID=?";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "si", $schedule_name, $userID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$check = mysqli_stmt_num_rows($stmt);
		if($check>0) {
			header("Location: ".$_SERVER['PHP_SELF']."?error=schedulenametaken");
			exit();
		}

		// Insert new schedule to schedule table
		$sql = "INSERT INTO schedule (schedule_name, schedule_date, userID) VALUES (?,?,?)";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ssi", $schedule_name, $schedule_date, $userID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		// get Schedule id
		$sql = "SELECT schedule_id FROM schedule WHERE schedule_name=? AND userID=?";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "si", $schedule_name, $userID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $schedule_id);
		mysqli_stmt_fetch($stmt);

		// After insert schedule, insert the tasks.
		$arrayOfTasks = array(
			array($_POST['task1'], $_POST['startTask1'], $_POST['endTask1']), //Task 1
			array($_POST['task2'], $_POST['startTask2'], $_POST['endTask2']), //Task 2
			array($_POST['task3'], $_POST['startTask3'], $_POST['endTask3']), //Task 3
			array($_POST['task4'], $_POST['startTask4'], $_POST['endTask4']), //Task 4
			array($_POST['task5'], $_POST['startTask5'], $_POST['endTask5']), //Task 5
			array($_POST['task6'], $_POST['startTask6'], $_POST['endTask6']), //Task 6
			array($_POST['task7'], $_POST['startTask7'], $_POST['endTask7']), //Task 7
			array($_POST['task8'], $_POST['startTask8'], $_POST['endTask8']), //Task 8
			array($_POST['task9'], $_POST['startTask9'], $_POST['endTask9']), //Task 9
			array($_POST['task10'], $_POST['startTask10'], $_POST['endTask10']), //Task 10
			array($_POST['task11'], $_POST['startTask11'], $_POST['endTask11']), //Task 11
			array($_POST['task12'], $_POST['startTask12'], $_POST['endTask12']), //Task 12
			array($_POST['task13'], $_POST['startTask13'], $_POST['endTask13']), //Task 13
			array($_POST['task14'], $_POST['startTask14'], $_POST['endTask14']), //Task 14
		);

		foreach ($arrayOfTasks as $taskInfo) {
			// check if name is empty
			if($taskInfo[0] == '') {
				continue;
			}
			else {
				// Insert task
				$sql = "INSERT INTO task (task_name, task_start_time, task_end_time, schedule_id) VALUES (?,?,?,?)";
				$stmt = mysqli_stmt_init($conn);
				mysqli_stmt_prepare($stmt, $sql);
				mysqli_stmt_bind_param($stmt, "sssi", $taskInfo[0], $taskInfo[1], $taskInfo[2], $schedule_id);
				mysqli_stmt_execute($stmt);
				// debug
				//echo 'Successfully added '.$taskInfo[0];
			}
		}
		header("Location: ".$_SERVER['PHP_SELF']."?msg=success");
		exit();
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Schedule</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body background="background.jpg">
	<center>
		<div id="container">
			<br>
		<h2>CREATE SCHEDULE</h2>

		<form name='createSchedule' onSubmit="return validation()" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
			<label>Schedule Name: </label>
			<input type = "text" name = "scheduleName" id="scheduleName"/>
			<br><br>
			<label>Date : </label>
			<input oninput="checkDate()" type = "date" name = "date" id="date"/>
			<script>
				function checkDate() {
					var d = document.forms["createSchedule"]["date"].value;
					var today = new Date();
					var dd = String(today.getDate()).padStart(2, '0');
					var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
					var yyyy = today.getFullYear();

					today = yyyy + '-' + mm + '-' + dd;
					todayDate = new Date(today);
					selectedDate = new Date(d);
					var e = todayDate-selectedDate;
					if(e>0) {
						alert("Invalid Date");
					}
				}
			</script>

		<br><br>
		<table border="3">
			<tr>
				<th colspan="2">Time</th>
				<th rowspan="2" >Task</th>
			</tr>
			<tr>
				<th>Start</th>
				<th>End</th>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask1" /></td>
				<td><input type = "time" name = "endTask1" /></td>
				<td><input type="text" name="task1" placeholder="Task 1"></td>
			</tr>
			<tr>
				<td><input type = "time" id="task2Start" name = "startTask2" oninput="setRequired()"/></td>
				<td><input type = "time" id="task2End" name = "endTask2" /></td>
				<td><input type="text" id="task2" name="task2" placeholder="Task 2" ></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask3" /></td>
				<td><input type = "time" name = "endTask3" /></td>
				<td><input type="text" name="task3" placeholder="Task 3"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask4" /></td>
				<td><input type = "time" name = "endTask4" /></td>
				<td><input type="text" name="task4" placeholder="Task 4"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask5" /></td>
				<td><input type = "time" name = "endTask5" /></td>
				<td><input type="text" name="task5" placeholder="Task 5"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask6" /></td>
				<td><input type = "time" name = "endTask6" /></td>
				<td><input type="text" name="task6" placeholder="Task 6"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask7" /></td>
				<td><input type = "time" name = "endTask7" /></td>
				<td><input type="text" name="task7" placeholder="Task 7"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask8" /></td>
				<td><input type = "time" name = "endTask8" /></td>
				<td><input type="text" name="task8" placeholder="Task 8"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask9" /></td>
				<td><input type = "time" name = "endTask9" /></td>
				<td><input type="text" name="task9" placeholder="Task 9"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask10" /></td>
				<td><input type = "time" name = "endTask10" /></td>
				<td><input type="text" name="task10" placeholder="Task 10"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask11" /></td>
				<td><input type = "time" name = "endTask11" /></td>
				<td><input type="text" name="task11" placeholder="Task 11"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask12" /></td>
				<td><input type = "time" name = "endTask12" /></td>
				<td><input type="text" name="task12" placeholder="Task 12"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask13" /></td>
				<td><input type = "time" name = "endTask13" /></td>
				<td><input type="text" name="task13" placeholder="Task 13"></td>
			</tr>
			<tr>
				<td><input type = "time" name = "startTask14" /></td>
				<td><input type = "time" name = "endTask14" /></td>
				<td><input type="text" name="task14" placeholder="Task 14"></td>
			</tr>
			
		</table>
		<br>
		<input type="submit" name="create-schedule-submit" value="Save" class="btn-save">
	</form>

	<script>

		function validation(){
			var v = document.forms["createSchedule"]["scheduleName"].value;
			var w = document.forms["createSchedule"]["date"].value;
			var x = document.forms["createSchedule"]["startTask1"].value;
			var y = document.forms["createSchedule"]["endTask1"].value;
			var z = document.forms["createSchedule"]["task1"].value;
			var x2 = document.forms["createSchedule"]["startTask2"].value;
			var y2 = document.forms["createSchedule"]["endTask2"].value;
			var z2 = document.forms["createSchedule"]["task2"].value;
			var x3 = document.forms["createSchedule"]["startTask3"].value;
			var y3 = document.forms["createSchedule"]["endTask3"].value;
			var z3 = document.forms["createSchedule"]["task3"].value;
			var x4 = document.forms["createSchedule"]["startTask4"].value;
			var y4 = document.forms["createSchedule"]["endTask4"].value;
			var z4 = document.forms["createSchedule"]["task4"].value;
			var x5 = document.forms["createSchedule"]["startTask5"].value;
			var y5 = document.forms["createSchedule"]["endTask5"].value;
			var z5 = document.forms["createSchedule"]["task5"].value;
			var x6 = document.forms["createSchedule"]["startTask6"].value;
			var y6 = document.forms["createSchedule"]["endTask6"].value;
			var z6 = document.forms["createSchedule"]["task6"].value;
			var x7 = document.forms["createSchedule"]["startTask7"].value;
			var y7 = document.forms["createSchedule"]["endTask7"].value;
			var z7 = document.forms["createSchedule"]["task7"].value;

			if (v ==""){
				alert("Schedule Name must be filled")
				return false;
			}
			else if (w == ""){
				alert("Date must be filled out before sumbit")
				return false;
			}
			else if (x == "" || y == "" || z == ""){
				alert("Please enter at least a TASK with START and END time.");
				return false;
				}
			else if (x > y){
				alert("Invalid Time");
				return false;
			}
			else if (x2 > y2){
				alert("Invalid Time");
				return false;
			}
			else if (x3 > y3){
				alert("Invalid Time");
				return false;
			}
			else if (x4 > y4){
				alert("Invalid Time");
				return false;
			}
			else if (x5 > y5){
				alert("Invalid Time");
				return false;
			}
			else if (x6 > y6){
				alert("Invalid Time");
				return false;
			}
			else if (x7 > y7){
				alert("Invalid Time");
				return false;
			}
		}

		function setRequired(){
			element = document.getElementById("task2");
			element2 = document.getElementById("task2End");
			element.required=true;
			element2.required=true;
	}

	</script>
	
	<a href="index.php"><button class="button" onclick="goBack()">Back</button>
	
</div>

	</center>

</body>
</html>