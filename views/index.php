<?php
	// include "../includes/init.php"  // creates db SciBowlSim
	// include "../includes/mkTb.php"  // makes table Round
	// include "../includes/popTb.php" // populates table Round
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
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#">About</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<h3 class="text-muted">MySciBowlSimulator!</h3>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<form class="form-response" role="form" id="form-response" action="#" onsubmit="retrieveResponse(); return false;">
					<input class="form-control" id="response-input" type="text" disabled="disabled" autocomplete="off" placeholder="Guess" required autofocus>
				</form>
			</div>
			<div class="col-sm-3">
				<div class="counterContainer">
					<a class="btn btn-default timer" id="roundTimer" href="#" role="button"></a>
					<a class="btn btn-default timer" id="buzzer" href="#" role="button"></a>
				</div>
		   	</div>
		   	<div class="col-sm-2">
				<div class="counterContainer">
					<a class="btn btn-default timer" id="score" href="#" role="button"></a>					
				</div>
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

	

	<script>
		runGame();
	</script>
</body>
</html>