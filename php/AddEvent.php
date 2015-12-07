<?php

#connect to mysql#############################################################
//read password stored not on the web server
$file = fopen("./sqlinfo.txt", "r");
$line = trim(fgets($file));
fclose($file);

// Connect to the MySQL database
$host = "fall-2015.cs.utexas.edu";
$user = "karen129";
$pwd = "$line";
$dbs = "cs329e_karen129";
$port = "3306";

$connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);

if (empty($connect))
{
  die("mysqli_connect failed: " . mysqli_connect_error());
}
 
############################################################################
if (isset($_POST["AddEvent"])){
	unset($_POST["AddEvent"]);
	$id = $_POST["id"];
	$name = $_POST["name"];
	$date= $_POST["date"];
	$starttime = $_POST["starttime"];
	$endtime = $_POST["endtime"];
	$location = $_POST["location"];
	$description = $_POST["description"];
	$capacity = $_POST["capacity"];
	$contact = $_POST["contact"];
	$cost = $_POST["cost"];

	
	$stmt = mysqli_prepare($connect, "insert into Events values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	mysqli_stmt_bind_param($stmt, 'issssssisi', $id, $name, $date, $starttime, $endtime, $location, $description, $capacity, $contact, $cost);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	header("Location:./dashboard.php");
	
} else {
	AddEvent();
}

function AddEvent(){
	$script = $_SERVER['PHP_SELF'];

	print<<<Register
	<form action = "$script" method = "post">
	<table border = "0">
	  <tr>
	  <td> EventID (Enter a 4-digit number)</td>
	  <td> <input type = "number" name = "id" maxlength = "4" required/> </td>
	  </tr>
	  <tr>
	  <td> Event Name</td>
	  <td> <input type = "text" name = "name" required/> </td>
	  </tr>
	  <tr>
	  <td> Date (xxxx-xx-xx)</td>
	  <td> <input type = "text" name = "date" required/> </td>
	  </tr>
	  <tr>
	  <td> Start Time (xx:xx:xx) </td>
	  <td> <input type = "text" name = "starttime" required/> </td>
	  </tr>
	  <tr>
	  <td> End Time (xx:xx:xx) </td>
	  <td> <input type = "text" name = "endtime" required/> </td>
	  </tr>
	  <tr>
	  <td> Location </td>
	  <td> <input type = "text" name = "location" required/> </td>
	  </tr>
	  <tr>
	  <td> Description </td>
	  <td> <input type = "text" name = "description" rows = "4" cols = "25" required/> </td>
	  </tr>
	  <tr>
	  <td> Capacity </td>
	  <td> <input type = "number" name = "capacity" required/> </td>
	  </tr>
	  <tr>
	  <td> Officer's Username </td>
	  <td> <input type = "text" name = "contact" required/> </td>
	  </tr>
	  <tr>
	  <td> Cost </td>
	  <td> <input type = "number" name = "cost" required/> </td>
	  </tr>
	  <tr>
	  <td> <input type = "submit" name = "AddEvent" value = "Add Event" /> </td>
	  <td> <input type = "reset" value = "Clear Form" /> </td>
	  </tr>
	</table>
	</form>
Register;
  
}


?>
