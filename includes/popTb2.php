<?php
	/***********************************************************************************
	* popTb2.php reads and XML file which has all the questions, rather than a text file
	*
	*******************************************/
	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	
	// Deletes all information from the table
	mysqli_query($con, "DELETE FROM Round");
	
	$xml = simplexml_load_file("set.xml");
	foreach($xml->question as $quest){
		$arr = array();
		$arr[0] = $quest->match;
		if(strcmp(substr($quest->type,0,1),"T")==0){
			$arr[1] = 4;
		}else{
			$arr[1] = 10;
		}
		$arr[2] = $quest->topic;
		$arr[3] = $quest->format;
		$arr[4] = $quest->problem;
		$arr[5] = $quest->solution;
		mysqli_query($con, "INSERT INTO Round (qnum, qtype, qtopic, qformat, question, solution) VALUES ('$arr[0]', '$arr[1]', '$arr[2]', '$arr[3]', '$arr[4]', '$arr[5]')");
		// mysqli_query($con, "INSERT INTO ROUND1 (TOSS_OR_BONUS, TOPIC, FORMAT, QUESTION, SOLUTION) VALUES ('a', 'b', 'c', 'd', 'e')");
	}
?>