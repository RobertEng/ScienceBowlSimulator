<?php
  $con = mysqli_connect("localhost", "root", "mysql");
	mysqli_select_db($con, "SciBowlSim");
	
  // Create Table
  // mysql_query("DROP TABLE IF EXISTS 'SciBowlSim'.'ROUND1'")

  $sql = "CREATE TABLE Round(qnum BIGINT, qtype INT, qtopic CHAR(32), qformat CHAR(16), question VARCHAR(1024), solution VARCHAR(512))";

  if (mysqli_query($con, $sql)) {
  	echo '<script>alert("TABLE Round created successfully");</script>';
  }
  else {
  	echo '<script>alert("Error creating TABLE")</script>';
  }
?>