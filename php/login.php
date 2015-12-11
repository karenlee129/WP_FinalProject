<?php

// Connect to the MySQL database
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

session_start();
if (!isset($_SESSION["name"])) {
$user = $_POST["user"];
$_SESSION["name"] = $user;
$pass = $_POST["pass"];
$user_pass = $user . ':' . $pass;
$verified = FALSE;

$sql = "SELECT Username, Password FROM Users WHERE Username = '$user';";
$ary = mysqli_fetch_array(mysqli_query($connect, $sql));
$line = $ary[0].':'.$ary[1];
if($line == $user_pass){
     $verified = TRUE;

}
fclose($file);
if($verified){
    header ("Location: ./dashboard.php");}

else{
    $_SESSION['errors'] = array("Your username or password was incorrect.");
    header("Location:../index.html");
}
}
else {
header("Location:./dashboard.php");}

function user_homepage(){
    print <<<PAGE
    <html>
    <h1>login success</h1>
    </html>
PAGE;
}


?>