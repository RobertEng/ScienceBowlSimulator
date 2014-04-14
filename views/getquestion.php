<?php
	// set MIME type
	header("Content-type: application/json");

	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	#$con = mysqli_connect("localhost", "903286_root", "mysql", "reng_zxq_scibowlsim");

	/*$sql = sprintf("SELECT * FROM Round WHERE matchNum='%s' AND pointVal='%s'",
		mysql_real_escape_string($_GET['matchNum']),
		mysql_real_escape_string($_GET['pointVal']));*/
	$sql = sprintf("SELECT * FROM Round WHERE matchNum='%s' AND pointVal='%s'",$_GET['matchNum'],$_GET['pointVal']);
	$mysql_result = mysqli_query($con, $sql);
	
	// fetch row
	$row = mysqli_fetch_assoc($mysql_result);

	$topic   = $row['topic'];
	$format  = $row['format'];
	$problem = $row['problem'];
	$q_arr    = json_encode(explode(" ", $problem));
	$solution = $row['solution'];

	// output JSON (Javascript object)
	print("{topic: '$topic', format: '$format', problem: $q_arr, solution: '$solution'}");
?>