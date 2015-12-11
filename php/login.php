<?php
session_start();
if (!isset($_SESSION["name"])) {
$user = $_POST["user"];
$_SESSION["name"] = $user;
$pass = $_POST["pass"];
$user_pass = $user . ':' . $pass;
$file = fopen("./passwd.txt", "r");
$verified = FALSE;
while(!feof($file)){
    $line = fgets($file);
    $line = trim($line);
    if($line == $user_pass){
        $verified = TRUE;
        break;
    }
}
fclose($file);
if($verified){
    user_homepage();
}

else{
    $_SESSION['errors'] = array("Your username or password was incorrect.");
    header("Location:../index.html");
}
}
else {
header ("Location: ./dashboard.php");}

function user_homepage(){
    print <<<PAGE
    <html>
    <h1>login success</h1>
    </html>
PAGE;
}


?>