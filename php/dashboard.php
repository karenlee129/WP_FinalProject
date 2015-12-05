<?php
#<script type='text/javascript' src='./dinner.js'></script>
#<link rel='stylesheet' type='text/css' href='./dinner.css' />

$script = $_SERVER['PHP_SELF'];
print <<<TOP
<html>
<head>
<title> Dashboard </title>
</head>
<body>
<h1><center>Welcome to your Dashboard </center></h1>
<br/>
TOP;

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

#################################################################################
$username = "chironly"; 

if(isset($_POST["signup"])){
	unset($_POST["signup"]);
	$EventID = $_POST["EventID"];
	if(strlen($EventID) !==0){
		$stmt = mysqli_prepare($connect, "insert into Going values (?, ?)");
		mysqli_stmt_bind_param($stmt, 'ss', $username, $EventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

if(isset($_POST["cancel"])){
	unset($_POST["cancel"]);
	$EventID = $_POST["EventID"];
	if(strlen($EventID) !==0){
		$stmt = mysqli_prepare($connect, "delete from Going where Username = ? and EventID = ?");
		mysqli_stmt_bind_param($stmt, 'ss', $username, $EventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

if(isset($_POST["ViewSignupList"])){
	unset($_POST["ViewSignupList"]);
	$EventID = $_POST["EventID"];
	if(strlen($EventID) !==0){
		displaySignups($connect, $EventID);
	}
}

SignupCancel();
AddEventButton();
ViewSignupList();
displayGoing($connect, $username);
displayNotGoing($connect, $username);


function SignupCancel(){
	$script = $_SERVER['PHP_SELF'];

	print<<<SignupCancel
	<form action = "$script" method = "post">
	<table border = "0">
	  <tr>
	  <td> Event ID </td>
	  <td> <input type = "text" name = "EventID" /> </td>
	  </tr>
	  <tr>
	  <td> <input type = "submit" name = "signup" value = "Sign Up" /> </td>
	  <td> <input type = "submit" name = "cancel" value = "Cancel" /> </td>
	  </tr>
	</table>
	</form>
SignupCancel;
}

function displayGoing($connect, $username) {
	print"
	<h3><center>Events you're signed up for </center></h3>
	<table align = 'center' border = '1' width = 80%>
	<tr>
	<th>Event ID</th>
	<th>Event Name</th>
	<th>Date</th>
	<th>Start Time</th>
	<th>End Time</th>
	<th>Location</th>
	</tr>";

	$going = "
	SELECT Events.EventID, Events.Name, Events.EventDate, Events.StartTime, Events.EndTime, Events.Location
	FROM Going INNER JOIN Events ON Going.EventID = Events.EventID
	WHERE Username = '".$username."'";

	$result = mysqli_query($connect, $going);
	while ($row = $result->fetch_row())
	{	

		print"
		<tr>
		<td>".$row[0]."</td>
		<td>".$row[1]."</td>
		<td>".$row[2]."</td>
		<td>".$row[3]."</td>
		<td>".$row[4]."</td>
		<td>".$row[5]."</td>
		</tr>";
	}

	$result->free();	

	print"
	</table>
	<br/><br/>";
}

function displayNotGoing($connect, $username) {
	print"
	<h3> <center>Events you're not signed up for </center></h3>
	<table align = 'center' border = '1' width = 80%>
	<tr>
	<th>Event ID </th>
	<th>Event Name</th>
	<th>Date</th>
	<th>Start Time</th>
	<th>End Time</th>
	<th>Location</th>
	</tr>";

	$going = "
	SELECT EventID, Name, EventDate, StartTime, EndTime, Location
	FROM Events 
	WHERE EventID not in (SELECT EventID from Going WHERE Username = '".$username."')"; 

	$result = mysqli_query($connect, $going);
	while ($row = $result->fetch_row())
	{	

		print"
		<tr>
		<td>".$row[0]."</td>
		<td>".$row[1]."</td>
		<td>".$row[2]."</td>
		<td>".$row[3]."</td>
		<td>".$row[4]."</td>
		<td>".$row[5]."</td>
		</tr>";
	}

	$result->free();	

	print"
	</table>
	<br/><br/>";
}

function AddEventButton() {
	print "<button name = 'AddEvent'>Add Event</button>";
}

function ViewSignupList() {
	$script = $_SERVER['PHP_SELF'];

	print<<<ViewSignups
	<form action = "$script" method = "post">
	<table border = "0">
	  <tr>
	  <td> Event ID </td>
	  <td> <input type = "text" name = "EventID" /> </td>
	  </tr>
	  <tr>
	  <td> <input type = "submit" name = "ViewSignupList" value = "View Signup List " /> </td>
	  </tr>
	</table>
	</form>
ViewSignups;
}

function displaySignups($connect, $EventID) {
	$result = mysql_query($connect, "select Name from Events where EventID = $EventID");
	$EventName = $result->fetch_row();
	$result->free();

	$result = mysql_query($connect, "select count(Username) from Going where EventID = $EventID");
	$numSigned = $result->fetch_row();
	$result->free();

	print"
	<h3><center>Here is a list of people who signed up to attend event ".$EventID.":".$EventName[0]."</center></h3>
	<h4><center>There are ".$numSigned[0]." people signed up for this event</center></h4>
	<table align = 'center' border = '1' width = 80%>
	<tr>
	<th>Name</th>
	</tr>";

	$going = "
	SELECT FName || ' ' || LName 
	FROM Users
	WHERE Username in (SELECT Username FROM Going WHERE EventID = $EventID)";

	$result = mysqli_query($connect, $going);
	while ($row = $result->fetch_row())
	{	
		print"
		<tr>
		<td>".$row[0]."</td>
		</tr>";
	}

	$result->free();	

	print"
	</table>
	<br/><br/>";
}

//close connection to database
mysqli_close($connect);

print <<<BOTTOM
</body>
</html>
BOTTOM;

?>