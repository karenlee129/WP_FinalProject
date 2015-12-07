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
if (isset($_POST["RegisterOrg"])){
	unset($_POST["Registerorg"]);
	$id = $_POST["id"];
	$name = $_POST["name"];
	$description = $_POST["description"];

	if(strlen($id) !==0 and $strlen($name) !==0 and strlen($description) !==0){
		$stmt = mysqli_prepare($connect, "insert into Organizations values (?, ?, ?)");
		mysqli_stmt_bind_param($stmt, 'iss', $id, $name, $description);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	header("Location:./dashboard.php");
} else {
	registerorg();
}

function RegisterOrg(){
	$script = $_SERVER['PHP_SELF'];

	print<<<Register
	<form action = "$script" method = "post">
	<table border = "0">
	  <tr>
	  <td> Organization ID (please enter a 4-digit number) </td>
	  <td> <input type = "number" name = "id" maxlength = "4" required/> </td>
	  </tr>
	  <tr>
	  <td> Organization Name </td>
	  <td> <input type = "text" name = "name" required/> </td>
	  </tr>
	  <tr>
	  <td> Organization Description </td>
	  <td> <input type = "textarea" name = "description" required/> </td>
	  </tr>
	  <tr>
	  <td> <input type = "submit" name = "RegisterOrg" value = "Register" /> </td>
	  </tr>
	</table>
	</form>
Register;
  
}


?>
