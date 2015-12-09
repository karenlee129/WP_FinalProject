<?php
  //session_start();
  //if (isset($_SESSION["NAME"])) {
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

  $ID = mysqli_real_escape_string($connect, "0002");
  $sql = "SELECT * FROM Events WHERE EventID = '$ID';";
  $sql1 = "SELECT Cost FROM Events WHERE EventID = '$ID';";
  $ary = mysqli_fetch_array(mysqli_query($connect, $sql));
  echo ("<table border='1px'><tr><td colspan='2' ALIGN=CENTER>Event Info</td></tr><tr><td>"."Event Name</td><td>".$ary[1]."</td></tr><tr><td>Event Date</td><td>".$ary[2]."</td></tr><tr><td>Event Time</td><td>".$ary[3]." - ".$ary[4]."</td></tr><tr><td>Event Location</td><td>".$ary[5]."</td></tr><tr><td>Event Description</td><td>".$ary[6]."</td></tr><tr><td>Event Cost</td><td>".$ary[9]."</td></tr>");//."</td><td>".$ary[9]."</td></tr>\n");
  // Close connection to the database
  $sql2 = "SELECT * FROM Users WHERE Username = '$ary[8]';";
  $sql3 = "SELECT * FROM Users;";
  $ary2 = mysqli_fetch_array(mysqli_query($connect, $sql2));
  echo ("<tr><td>Officer's Name</td><td>".$ary2[2]." ".$ary2[3]."</td></tr><tr><td>Officer's Email</td><td>".$ary2[5]."</td></tr><tr><td>Officer's Number</td><td>".$ary2[4]."</td></tr></table>");
  mysqli_close($connect);
  //}
  //else {
  //header ("Location: login.html");
  //}
?>
