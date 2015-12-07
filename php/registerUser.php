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
		mysqli_stmt_bind_param($stmt, 'ssssis', $username, $password, $fname, $lname, $phone, $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		header("Location:../index.html");
	}
	
} else {
	registerUser();
}

function RegisterUser(){
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

    <title>Gather User Account Creation</title>

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
                        <h1>User Account Creation</h1>

TOP;

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

}


?>
