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
if (isset($_POST["RegisterUser"])){
	unset($_POST["RegisterUser"]);
	$username = $_POST["username"];
	$password = $_POST["password"];
	$repeatpassword= $_POST["repeatpassword"];
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];

	if($password==$repeatpassword){
		$stmt = mysqli_prepare($connect, "insert into Users values (?, ?, ?, ?, ?, ?)");
		mysqli_stmt_bind_param($stmt, 'ssssss', $username, $password, $fname, $lname, $phone, $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	header("Location:../index.html");
} else {
	registerUser();
}

function RegisterUser(){
	$script = $_SERVER['PHP_SELF'];

	print<<<Register
	<form action = "$script" method = "post">
	<table border = "0">
	  <tr>
	  <td> Username </td>
	  <td> <input type = "text" name = "username" required/> </td>
	  </tr>
	  <tr>
	  <td> Password</td>
	  <td> <input type = "text" name = "password" required/> </td>
	  </tr>
	  <tr>
	  <td> Repeat Password</td>
	  <td> <input type = "text" name = "repeatpassword" required/> </td>
	  </tr>
	  <tr>
	  <td> First Name </td>
	  <td> <input type = "text" name = "fname" required/> </td>
	  </tr>
	  <tr>
	  <td> Last Name </td>
	  <td> <input type = "text" name = "lname" required/> </td>
	  </tr>
	  <tr>
	  <td> Phone Number </td>
	  <td> <input type = "tel" name = "phone" required/> </td>
	  </tr>
	  <tr>
	  <td> E-mail </td>
	  <td> <input type = "email" name = "email" required/> </td>
	  </tr>
	  <tr>
	  <td> <input type = "submit" name = "RegisterUser" value = "Register" /> </td>
	  <td> <input type = "reset" value = "Clear Form" /> </td>
	  </tr>
	</table>
	</form>
Register;
  
}


?>
