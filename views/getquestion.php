<?php
	// set MIME type
	header("Content-type: application/json");

	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	$sql = sprintf("SELECT * FROM Round WHERE qnum='%s' AND qtype='%s'",
		mysql_real_escape_string($_GET['qnum']),
		mysql_real_escape_string($_GET['qtype']));
	$mysql_result = mysqli_query($con, $sql);
	
	// fetch row
	$row = mysqli_fetch_assoc($mysql_result);

	$qtopic   = $row['qtopic'];
	$qformat  = $row['qformat'];
	$question = $row['question'];
	$q_arr    = json_encode(explode(" ", $question));
	$solution = $row['solution'];

	// output JSON (Javascript object)
	print("{qtopic: '$qtopic', qformat: '$qformat', question: $q_arr, solution: '$solution'}");
?>