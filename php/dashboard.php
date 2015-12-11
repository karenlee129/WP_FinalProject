<?php
#<script type='text/javascript' src='./dinner.js'></script>
#<link rel='stylesheet' type='text/css' href='./dinner.css' />

$script = $_SERVER['PHP_SELF'];
print <<<TOP

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gather Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/scrolling-nav.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<!-- The #page-top ID is part of the scrolling feature - the data-spy and data-target are part of the built-in Bootstrap scrollspy function -->

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="../index.html">Gather</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a class="page-scroll" href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Services</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Intro Section -->
            <section id="intro" class="intro-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                                <h1 class="section-heading">Welcome To Your Dashbaord!!</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">

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
session_start();

$username = $_SESSION["name"]; 

$going = "
SELECT Officer
FROM Organizations";

$officer = false;
$result = mysqli_query($connect, $going);
$officers = $result->fetch_row();
$i = 0;
while ($name = $officers[$i])
{
	if ($username == $name){
		$officer = true;
	}

	$i = $i + 1;
}


#if the user clicks the signup button to sign up for an event
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

#if the user clicks the cancel button to cancel an event
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

#if the user clicks the View Signup List button, function displaySignups will be called to display a list of people signed up
if(isset($_POST["ViewSignupList"])){
	unset($_POST["ViewSignupList"]);
	$EventID = $_POST["EventID"];
	if(strlen($EventID) !==0){
		displaySignups($connect, $EventID);
	}
}

if(isset($_POST["AddEvent"])){
	header("Location:./AddEvent.php");
}

#if user clicks the Delete Event button
if(isset($_POST["delete"]))
{
	unset($_POST["delete"]);
	$id = $_POST["id"];

	if (strlen($id)!==0)
	{
		$stmt = mysqli_prepare($connect,"delete from Events where EventID = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		$stmt = mysqli_prepare($connect,"delete from Going where EventID = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}	
}

#if user clicks "More Details" button
if(isset($_POST["details"])){
	unset($_POST["details"]);
	$id = $_POST["EventID"];
	details($connect, $id);
}

#what's visible to the user (this part will change depending on whether or not the user is an officer)

if (!$officer){
	print "Sign up or cancel for an event here:"; 
	SignupCancel();
	print"<br /><br />";

	print "View all the details to a particular event here:";
	detailsform();
	print"<br /><br />";

	displayGoing($connect, $username);
	displayNotGoing($connect, $username);

} else {

	print "Sign up or cancel for an event here:"; 
	SignupCancel();
	print"<br /><br />";

	print "View all the details to a particular event here:";
	detailsform();
	print"<br /><br />";

	print "See who's signed up for a particular event here:";
	ViewSignupList();
	print"<br /><br />";

	displayGoing($connect, $username);
	displayNotGoing($connect, $username);

	print "Add a new event here:";
	AddEventButton();
	print"<br /><br />";

	print "Delete an event here:";
	DeleteEventButton();
}

#form to view event details
function detailsform(){
	$script = $_SERVER['PHP_SELF'];

	print<<<details
	<form action = "$script" method = "post">
	<div class = "row">
	<table border = "0" class = "col-lg-12 table-centered">
	  <tr>
	  <td class="col-md-6"> Event ID </td>
	  <td class="col-md-6"> <input type = "text" name = "EventID" class="form-control"/> </td>
	  </tr>
	  <tr>
	  <td class="col-md-6"> <input type = "submit" name = "details" class="btn btn-xl" value = "View Details" /> </td>
	  </tr>
	</table>
	</div>
	</form>
details;
}

function DeleteEventButton() {
	$script = $_SERVER['PHP_SELF'];

	print<<<DELETE
	<form action = "$script" method = "post">
	<div class = "row">
	<table border = "0" class = "col-lg-12 table-centered">
	  <tr>
	  <td class="col-md-6"> Event ID </td>
	  <td class="col-md-6"> <input type = "text" name = "id" class="form-control" /> </td>
	  </tr>
	  <tr>
	  <td class="col-md-6"> <input type = "submit" class="btn btn-xl" name = "delete" value = "Delete Event" /> </td>
	  </tr>
	</table>
	</div>
	</form>

DELETE;
}

#form to signup or cancel for an event
function SignupCancel(){
	$script = $_SERVER['PHP_SELF'];

	print<<<SignupCancel
	<form action = "$script" method = "post">
	<div class = "row">
	<table border = "0" class = "col-lg-12 table-centered">
	  <tr>
	  <td class="col-md-6"> Event ID </td>
	  <td class="col-md-6"> <input type = "text" name = "EventID" class="form-control" /> </td>
	  </tr>
	  <tr>
	  <td class="col-md-6"> <input type = "submit" name = "signup" class="btn btn-xl" value = "Sign Up" /> </td>
	  <td class="col-md-6"> <input type = "submit" name = "cancel" class="btn btn-xl" value = "Cancel" /> </td>
	  </tr>
	</table>
	</div>
	</form>
SignupCancel;
}

#displays details of an event given EventID
function details($connect, $id) {
	$ID = mysqli_real_escape_string($connect, $id);
  	$sql = "SELECT * FROM Events WHERE EventID = '$ID';";

 	$ary = mysqli_fetch_array(mysqli_query($connect, $sql));
  	print"
  	<table border='1px'>
    <tr><td colspan='2' ALIGN=CENTER>Event Info</td></tr>
    <tr><td>"."Event Name</td><td>".$ary[1]."</td></tr>
    <tr><td>Event Date</td><td>".$ary[2]."</td></tr>
    <tr><td>Event Time</td><td>".$ary[3]." - ".$ary[4]."</td></tr>
    <tr><td>Event Location</td><td>".$ary[5]."</td></tr>
    <tr><td>Event Description</td><td>".$ary[6]."</td></tr>
    <tr><td>Event Cost</td><td>".$ary[9]."</td></tr>";

	$sql2 = "SELECT * FROM Users WHERE Username = '$ary[8]';";
	$ary2 = mysqli_fetch_array(mysqli_query($connect, $sql2));
	print "<tr><td>Officer's Name</td>
    <td>".$ary2[2]." ".$ary2[3]."</td></tr>
    <tr><td>Officer's Email</td>
    <td>".$ary2[5]."</td></tr>
    <tr><td>Officer's Number</td>
    <td>".$ary2[4]."</td></tr></table>";
}

#shows events the user has signed up for in tabular form
function displayGoing($connect, $username) {
	$script = $_SERVER['PHP_SELF'];
	print"
	<h3><center>Events you're signed up for </center></h3>
	<form action = '$script' method = 'post'>
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
	</form>	
	<br/><br/>";
}

#shows events the user has not signed up for in tabular form
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

#adds a button to add an event (officers only)
function AddEventButton() {
	print "
	<form action = '$script' method = 'post'>
	<input type = 'submit' name = 'AddEvent' class='btn btn-xl' value = 'Add Event' />
	</form>";
}

#form to request a list of people signed for an event(officers only)
function ViewSignupList() {
	$script = $_SERVER['PHP_SELF'];

	print<<<ViewSignups
	<form action = "$script" method = "post">
	<div class = "row">
	<table border = "0" class = "col-lg-12 table-centered">
	  <tr>
	  <td class="col-md-6"> Event ID </td>
	  <td class="col-md-6"> <input type = "text" name = "EventID" class="form-control" /> </td>
	  </tr>
	  <tr>
	  <td class="col-md-6"> <input type = "submit" name = "ViewSignupList" class="btn btn-xl" value = "View Signup List " /> </td>
	  </tr>
	</table>
	</div>
	</form>
ViewSignups;
}

#query to display a list of people going to a certain event (officers only)
function displaySignups($connect, $EventID) {
	$result = mysqli_query($connect, "select Name from Events where EventID = $EventID");
	$EventName = $result->fetch_row();
	$result->free();

	$result = mysqli_query($connect, "select count(Username) from Going where EventID = $EventID");
	$numSigned = $result->fetch_row();
	$result->free();

	print"
	<h3><center>There are $numSigned[0] people signed up for $EventName[0].</center></h3>
	<table align = 'center' border = '1' width = 80%>
	<tr>
	<th>First Name</th>
	<th>Last Name</th>
	<th>E-mail</th>
	<th>Phone Number</th>
	</tr>";

	$going = "
	SELECT FName, LName, Email, Phone 
	FROM Users
	WHERE Username in (SELECT Username FROM Going WHERE EventID = $EventID)";

	$result = mysqli_query($connect, $going);
	while ($row = $result->fetch_row())
	{	
		print"
		<tr>
		<td>".$row[0]."</td>
		<td>".$row[1]."</td>
		<td>".$row[2]."</td>
		<td>".$row[3]."</td>
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
</div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Scrolling Nav JavaScript -->
    <script src="../js/jquery.easing.min.js"></script>
    <script src="../js/scrolling-nav.js"></script>

</body>

</html>
BOTTOM;

?>