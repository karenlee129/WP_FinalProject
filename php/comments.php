<?php
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$comments = $_POST["comments"];

$host = "fall-2015.cs.utexas.edu";
$user = "ronly";
$pwd = "WKaCjv92wl";
$dbs = "cs329e_ronly";
$port = "3306";

$connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
$table = "comments";

$sql = "INSERT INTO $table VALUES (\"$name\", \"$email\", \"$phone\", \"$comments\")";

if(mysqli_query ($connect, $sql)){
    homepage_comments();
    }


function homepage_comments(){
    print <<<HP_COMMENTS
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Scrolling Nav - Start Bootstrap Template</title>

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
                    <a class="navbar-brand page-scroll" href="../index.html">Start Bootstrap</a>
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
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="page-scroll navbar-right" href="#login">
                                <form method = "POST" action = "./php/login.php">
                                    <table border = "0">
                                        <tr>
                                            <td> Username : </td>
                                            <td> <input type = "text" name = "user" id = "r_user" size = "20"/> </td>
                                            <td> Password : </td>
                                            <td> <input type = "password" name = "pass" size = "20"/> </td>
                                            <td> <input type = "submit" value = "Login" /> </td>
                                        </tr>
                                    </table>
                                </form>
                            </a>
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
                        <h1>Thank you for you feedback!!</h1>
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
HP_COMMENTS;


}

?>