<?php
	$con = mysqli_connect("localhost", "root", "mysql");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Create database
	$sql = "CREATE DATABASE SciBowlSim";
	if (mysqli_query($con, $sql)) {
		echo '<script>alert("DATABASE SciBowlSim created successfully");</script>';
	}
	else {
		echo '<script>alert("Error creating DATABASE")</script>';
	}
  	
  	mysqli_close($con);
?>