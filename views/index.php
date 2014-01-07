<?php
	// include "../includes/init.php"  // creates db SciBowlSim
	// include "../includes/mkTb.php"  // makes table Round
	// include "../includes/popTb.php" // populates table Round
	// include "../includes/conn.php"  // connects to SciBowlSim Database in MySQL
?>

<script>

// adjustable variables
var wrps = 2; // words read per second

var period = 1;
var qtype = 4; // 4 = toss-up, 10 = bonus
var roundClock;
var buzzerClock;
var roundCount = 120; // seconds per round
var buzzerCount; // 5 for tossup, 20 for bonus
var buzzedIn = false;
var score = 0;

var xhr = null;
var qnum = 0;

var currentQuestion;
var questionStart = false; // has a question begun being asked?

function getEditDistance(response, solution) {
	if (response.length == 0 && solution.length == 0) return 0;
	if (response == solution) return 0;
	if (response.length == 0 && solution.length != 0) return solution.length;
	if (response.length != 0 && solution.length == 0) return response.length;
	else {
		var sub = 0;
		if (response.charAt(0) == solution.charAt(0)) {
			sub = getEditDistance(response.substring(1), solution.substring(1));
		}
		else {
			sub = 1 + getEditDistance(response.substring(1), solution.substring(1));
		}
		var ins = 1 + getEditDistance(response, solution.substring(1));
		var del = 1 + getEditDistance(response.substring(1), solution);
		return Math.min(ins, del, sub);
	}
};

function trim(str) {
	while (str.charAt(0) === " ") {
		str = str.substring(1);
	}
	while (str.charAt(str.length-1) === " ") {
		str = str.substring(0, str.length-1);
	}
	return str;
};

function retrieveResponse() {
	buzzerCount = 0;
	var response = trim(String($('input[id=response-input]').val()));
	var currentSolution = currentQuestion.solution;
	

	if (getEditDistance(response.toLowerCase(), currentSolution.toLowerCase()) < 1) {
		alert("You got it!");
		score += qtype;
		document.getElementById("score").innerHTML = score;
		if (qtype == 4) {
			qtype = 10;
		} else {
			qtype = 4;
			qnum++;
		}
	} else {
		alert("Close but no cigar");
		qtype = 4;
		qnum++;
	}
	document.getElementById("form-response").reset();
	$('#response-input').prop('disabled', true);
	buzzedIn = false;
	var node = document.getElementById("question");
	while (node.firstChild) {
		node.removeChild(node.firstChild);
	}
	questionStart = false;

	readQuestion();
};

function buzzerCountDown() {
	if (buzzerCount < 10) {
		document.getElementById("buzzer").innerHTML = "0:0" + buzzerCount.toString();
	} else {
		document.getElementById("buzzer").innerHTML = "0:" + buzzerCount.toString();
	}
	
	if (buzzerCount == 0) {
		clearInterval(buzzerClock);
		
		// When form submitted prior to buzzerCount == 0, retrieveResponse()
		// called. To prevent retrieveResponse() from being called again when
		// the buzzerCount is set to zero, include the following if statement

		if (buzzedIn) {
			$('input[id=response-input]').submit();
		}
	}
	buzzerCount--;
};

function roundCountDown() {
	if (roundCount >= 0) {
		var minutes = Math.floor(roundCount / 60).toString();
		var seconds = roundCount % 60;
		if (seconds < 10) {
			seconds = "0" + seconds.toString();
		} else {
			seconds = seconds.toString();
		}
		var roundTime = minutes + ":" + seconds;
		document.getElementById("roundTimer").innerHTML = roundTime;
		roundCount--;
	}
};

function questionResponse() {
	buzzedIn = true;
	$('#response-input').prop('disabled', false); // textbox clickable
	$('#response-input').focus(); // textbox contains cursor on space
	if (qtype == 4) {
		buzzerCount = 5;
		buzzerClock = setInterval(function() {buzzerCountDown()}, 1000);
	} else {
		buzzerCount = 20;
		buzzerClock = setInterval(function() {buzzerCountDown()}, 1000);
	}
};

function readQuestion() {
	if (roundCount <= 0 && qtype == 4) {
		clearInterval(roundClock);
		period++;
		alert('Round done');
		return;
	}
	try {
		xhr = new XMLHttpRequest();
	}
	catch(e) {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	// handle old browsers
	if (xhr == null) {
		alert("Ajax not supported by your browser!");
		return;
	}

	// construct URL
	var url = "getquestion.php?qnum=" + qnum + "&qtype=" + qtype;
	// get question
	xhr.onreadystatechange = handler;
	xhr.open("GET", url, true);
	xhr.send(null);
};

function displayText(addtext, delay, bold) {
	setTimeout(function() {
		if (!buzzedIn) {
			var div = document.createElement("div");
			div.className = "qtext";
			var textNode = document.createTextNode(addtext + " ");
			div.appendChild(textNode);
			document.getElementById("question").appendChild(div);
		}
	}, delay);
};

function handler() {
	// only handle loaded requests
	if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			// evaluate JSON for current question
			// qtopic, qformat, question, solution

			// Convert JSON string to Javascript object
			currentQuestion = eval('(' + xhr.responseText + ')');
			// show JSON in alertbox
			// alert(xhr.responseText);
			
			if (qtype == 4) {
				displayText("Toss-up", 0);
			} else {
				displayText("Bonus", 0);
			}
			
			displayText(currentQuestion.qtopic, 1000/wrps);
			displayText(currentQuestion.qformat, 2*1000/wrps);

			questionStart = true;

			for (var i = 0; i < (currentQuestion.question).length; i++) {
				displayText((currentQuestion.question)[i], 3*1000/wrps + i*200);
			}
			setTimeout(function() {
				if (!buzzedIn) questionResponse();
			}, 3*1000/wrps + (currentQuestion.question).length * 200);
			
		} else {
			alert("Error with Ajax call!");
		}		
	}
};

function runGame() {
	roundClock = setInterval(function() {roundCountDown()}, 1000);
	document.getElementById("score").innerHTML = score;
	document.getElementById("roundTimer").innerHTML = "0:00";
	document.getElementById("buzzer").innerHTML = "0:00";

	readQuestion();
	$('body').keydown(function(e) {
		if (e.keyCode == 32 && !buzzedIn && questionStart) questionResponse();
	});
};

</script>

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



	<script>alert("Welcome to SciBowlSimulator!");</script>

	

	<script>
		runGame();
	</script>
</body>
</html>