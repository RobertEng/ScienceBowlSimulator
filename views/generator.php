<?php
	// include "../includes/init.php"  // creates db SciBowlSim
	// include "../includes/mkTb.php"  // makes table Round
	// include "../includes/popTb3.php" // populates table Round
	// include "../includes/conn.php"  // connects to SciBowlSim Database in MySQL
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="includes/favicon.png">

	<title>SciBowlSimulator App</title>

	<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/index.css" rel="stylesheet">

	<script src="js/jquery.js"></script>
	<script src="divideByThree.js"></script>
	<script src="SBjavascript.js"></script>

</head>
<body>
	
	<div class="container">
		<div class="header">
			<ul class="nav nav-pills pull-right">
				<li class="active"><a href="index.php">Home</a></li>
				<li><a href="generator.php">Generator</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<h3 class="text-muted">MySciBowlSimulator!</h3>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<select class="form-control" id="questDifficulty">
					<option>All</option>
					<option>Round 1-5</option>
					<option>Round 5-10</option>
					<option>Round 11-17</option>
					<option>Round 1-5 and Other</option>
					<option>Other</option>
				</select>
			</div>
			<div class="col-sm-4">
				<select class="form-control" id="questTopics">
					<option>All</option>
					<option>Astronomy</option>
					<option>Physics</option>
					<option>Chemistry</option>
					<option>Biology</option>
					<option>Math</option>
					<option>Earth Science</option>
					<option>Earth and Space</option>
					<option>General Science</option>
					<option>Computer Science</option>
					<option>Current Categories</option>
					<option>Other</option>
				</select>
		   	</div>
		   	<div class="col-sm-4">
		   	<select class="form-control" id="questQuantity">
		   		<option>5 Questions</option>
		   		<option>25 Questions</option>
		   		<option>50 Questions</option>
		   		<option>100 Questions</option>
		   		<option>200 Questions</option>
		   	</select>
		   	</div>
		</div>

		<div class="row">
			<div class="col-sm-4 col-sm-offset-5">
				<button type="button" class="btn btn-primary" onclick="generateQuests();">Primary</button>
			</div>
		</div>

		<div class="jumbotron">


			<!-- <h1>Jumbotron heading</h1> -->
			<p class="qtext" id="question"></p>
			<!-- <p><a class="btn btn-lg btn-success" href="#" role="button">Sign up today</a></p> -->
		</div>
	</div> <!-- /container -->


	<!--
	<script>alert("Welcome to SciBowlSimulator!");</script>
	-->

	
<!--
	<script>
		runGame();
	</script>
-->

</body>
</html>