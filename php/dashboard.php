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
<h1> Welcome to your Dashboard </h1>
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

displayGoing($connect);

displayNotGoing($connect);

function displayGoing($connect) {
	print"
	<h3> Events you're signed up for </h3>
	<table border = '1' width = 80%>
	<tr>
	<th>Event Name</th>
	<th>Date</th>
	<th>Start Time</th>
	<th>End Time</th>
	<th>Location</th>
	<th>Cancellation</th>
	</tr>";

	$going = "
	SELECT Events.Name, Events.EventDate, Events.StartTime, Events.EndTime, Events.Location
	FROM Going INNER JOIN Events ON Going.EventID = Events.EventID
	WHERE Username = 'chironly'";

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
		<td> link to cancellation page </td>
		</tr>";
	}

	$result->free();	

	print"
	</table>
	<br/><br/>";
}

function displayNotGoing($connect) {
	print"
	<h3> Events you're not signed up for </h3>
	<table border = '1' width = 80%>
	<tr>
	<th>Event Name</th>
	<th>Date</th>
	<th>Start Time</th>
	<th>End Time</th>
	<th>Location</th>
	<th>Sign-Up</th>
	</tr>";

	$going = "
	SELECT Events.Name, Events.EventDate, Events.StartTime, Events.EndTime, Events.Location
	FROM Events 
	WHERE EventID not in (SELECT EventID from Going WHERE Username = 'chironly')"; 

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
		<td> link to signup page </td>
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