<?php
	/***********************************************************************************
	* popTb3.php makes the mySQL table, reads the XML file which has all the questions,
	* all by using a nice line of SQL.
	*******************************************/
	//For localhost
	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	//For website. THIS DOESN"T WORK.
	#$con = mysqli_connect("localhost", "903286_root", "mysql", "reng_zxq_scibowlsim");

/*
	mysqli_query($con, "DROP TABLE Round;");  // Should delete the table
	
	$sql = "CREATE TABLE Round(matchNum BIGINT, type CHAR(32), topic CHAR(32), format CHAR(16), 
		origin CHAR(16), pointVal INT, problem VARCHAR(1024), solution VARCHAR(512))";

	if (mysqli_query($con, $sql)) {
	  	echo '<script>alert("TABLE Round created successfully");</script>';
	} else {
	  	echo '<script>alert("Error creating TABLE");</script>';
	}

	mysqli_query($con, "DELETE FROM Round");	// Deletes all information from the table
*/

	if(mysqli_query($con, "LOAD XML LOCAL INFILE 'set.xml' INTO TABLE Round ROWS IDENTIFIED BY '<question>'")) {
		echo '<script>alert("XML file loaded successfully");</script>';
	} else {
		echo '<script>alert("Error loading XML file");</script>';
	}
?>