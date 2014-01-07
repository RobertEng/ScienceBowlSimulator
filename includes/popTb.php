<?php
	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	
	// Deletes all information from the table
	mysqli_query($con, "DELETE FROM Round");
	
	$file = fopen("questions.txt","r");

	$curIndex = 0;
	$arr = array();
	$str = "";
	// echo "<script>alert('$str');</script>";
	
	while (!feof($file)) {
		$str .= fgetc($file); // PHP string concatenation
		// echo "<script>alert('$str');</script>";
		if (strcmp(substr($str, strlen($str)-2), "##") == 0) {
			$arr[$curIndex] = substr($str, 0, strlen($str)-2);
			$str = "";
			$curIndex++;
		}

		if ($curIndex == 6) {
			// fgetc($file);
			/*
			for ($i = 0; $i < count($arr); $i++) {
				echo "<script>alert('$arr[$i]');</script>";
			}
			*/
			mysqli_query($con, "INSERT INTO Round (qnum, qtype, qtopic, qformat, question, solution) VALUES ('$arr[0]', '$arr[1]', '$arr[2]', '$arr[3]', '$arr[4]', '$arr[5]')");
			// mysqli_query($con, "INSERT INTO ROUND1 (TOSS_OR_BONUS, TOPIC, FORMAT, QUESTION, SOLUTION) VALUES ('a', 'b', 'c', 'd', 'e')");
			$curIndex = 0;
		}
		
	}
?>