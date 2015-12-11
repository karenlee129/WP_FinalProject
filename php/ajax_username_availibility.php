<?php

$user = $_GET["user"];

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


$query = "
SELECT Username
FROM Users";

$officer = false;
$result = mysqli_query($connect, $query);
$usernames = $result->fetch_row();
$i = 0;
while ($name = $usernames[$i])
{
	if ($username == $name){
		$officer = true;
	}

	$i = $i + 1;
}

echo $officer;
?>