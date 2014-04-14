<?php
	$con = mysqli_connect("localhost", "root", "mysql", "SciBowlSim");
	#$con = mysqli_connect("localhost", "903286_root", "mysql", "reng_zxq_scibowlsim");

	if(isset($_GET['questDiff'])){
		$diff = $_GET['questDiff'];
		//Protects against msql injections, but doesn't work on real website
		//mysql_real_escape_string($diff);
	}
	//else // Set default later
	if(isset($_GET['questTop'])){
		$top = $_GET['questTop'];
		//mysql_real_escape_string($top);
	}
	//else // set default
	if(isset($_GET['questQuant'])){
		$quant = $_GET['questQuant'];
		//mysql_real_escape_string($quant);
	}
	//else // set default

	//print("'$diff' '$top' '$quant'<br>");

	// set MIME type
	header("Content-type: application/json");

	//Using the origin (aka Round #). Using it to determine difficulty
	if($diff == 'Round 1-5'){
		$diff="'ROUND 1','ROUND 2','ROUND 3','ROUND 4','ROUND 5'";
	} else if($diff == 'Round 5-10'){
		$diff="'ROUND 6','ROUND 7','ROUND 8','ROUND 9','ROUND 10'";
	} else if($diff == 'Round 11-17'){
		$diff="'ROUND 10','ROUND 11','ROUND 12','ROUND 13','ROUND 14','ROUND 15','ROUND 16','ROUND 17'";
	} else if($diff == 'All'){ //if($diff == 'All'){ //Default case
		$diff="'ROUND 1','ROUND 2','ROUND 3','ROUND 4','ROUND 5',
		'ROUND 6','ROUND 7','ROUND 8','ROUND 9','ROUND 10','ROUND 11',
		'ROUND 12','ROUND 13','ROUND 14','ROUND 15','ROUND 16','ROUND 17','Arizona'";
	} else if($diff == 'Round 1-5 and Other'){
		$diff="'ROUND 1','ROUND 2','ROUND 3','ROUND 4','ROUND 5','Arizona'";
	}else if($diff == 'Other'){
		$diff="'Arizona'";
	} else {

	}
	if($top == 'All'){
		$top = "'Astronomy','Math','Energy','Physics','Biology','Chemistry',
		'Earth and Space','Earth Science','Computer Science','General Science'";
	} else if($top == 'Current Categories'){
		$top = "'Math','Energy','Physics','Biology','Chemistry','Earth and Space'";
	}else {
		//need to add the single quotes around the topic
		$top = "'$top'";
	}

	preg_match_all('/\d+/', $quant, $matches);
	$quant = $matches[0][0];

	if(mysqli_connect_errno()) print "We just hit an error";
	//All this fancy stuff to stop MySQL injections
//	$sql = sprintf("SELECT * FROM Round WHERE topic in (%s) AND origin in (%s) 
//		ORDER BY RAND() LIMIT %s;",$top,$diff,$quant); //Old query which ignores tossup bonus.

	$sql = sprintf("SELECT * FROM Round WHERE topic in (%s) AND origin in (%s);",$top,$diff);
	$mysql_result = mysqli_query($con, $sql);

	$size = mysqli_num_rows($mysql_result)/2; //divide by 2 to account for Tossup+bonus as one Q

	$randQs = array(); //One array for the qnums ive picked. the qnum isn't related to matchNum
	$pickedQs = array(); //One array to check against so I don't get copies
	$pickedQs[$size]=FALSE; // So it doesn't have to keep reallocating bigger and bigger arrays
	for($a=0; $a<$size;$a++){ //False out pickedQs array
		$pickedQs[$a] = FALSE;
	}
	if($size < $quant){
		$quant = $size; //If num Qs I want is less than num available
	}
	for($e=0; $e<$quant; $e++){
		$candidate = mt_rand(0,$size-1);
		while($pickedQs[$candidate]){
			$candidate = mt_rand(0,$size-1);
		}
		$pickedQs[$candidate] = TRUE;
		$randQs[$e] = $candidate;
		#print("candidate/index of randomly selected q: $candidate e/index of randQs: $e<br>");
	}
	
	$allQs = array(); //Array to hold all the questions from mysql query
	while($row = mysqli_fetch_array($mysql_result)){
		$allQs[] = $row;
	}
	for($u=0; $u<sizeof($randQs); $u++){
		$matchNum = $allQs[$randQs[$u]*2]['matchNum'];
		$origin  = $allQs[$randQs[$u]*2]['origin'];
  		$type    = $allQs[$randQs[$u]*2]['type'];
  		$topic   = $allQs[$randQs[$u]*2]['topic'];
		$format  = $allQs[$randQs[$u]*2]['format'];
		$problem = $allQs[$randQs[$u]*2]['problem'];
		$solution = $allQs[$randQs[$u]*2]['solution'];
		$uPlus = $u+1;
		print("$uPlus. ID=$matchNum $origin<br>$type $topic $format<br>$problem<br>ANSWER: $solution<br><br>");

		//Need the Bonus question too
		$matchNum = $allQs[$randQs[$u]*2+1]['matchNum'];
		$origin  = $allQs[$randQs[$u]*2+1]['origin'];
  		$type    = $allQs[$randQs[$u]*2+1]['type'];
  		$topic   = $allQs[$randQs[$u]*2+1]['topic'];
		$format  = $allQs[$randQs[$u]*2+1]['format'];
		$problem = $allQs[$randQs[$u]*2+1]['problem'];
		$solution = $allQs[$randQs[$u]*2+1]['solution'];

		print("$type $topic $format<br>$problem<br>ANSWER: $solution<br><br>");
	}

	mysqli_close($con);
?>