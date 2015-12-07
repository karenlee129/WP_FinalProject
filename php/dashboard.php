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
                    <div class="col-lg-12">
                        <h1>Welcome to your dashboard!</h1>

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

#### need to edit later
#what's visible to the user (this part will change depending on whether or not the user is an officer)
print "Sign up or cancel for an event here:"; 
SignupCancel();
AddEventButton();
ViewSignupList();
displayGoing($connect, $username);
displayNotGoing($connect, $username);


#form to signup or cancel for an event
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

#shows events the user has signed up for in tabular form
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
	print "<button name = 'AddEvent'>Add Event</button>";
}

#form to request a list of people signed for an event(officers only)
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

###doesn't work yet
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