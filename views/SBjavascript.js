// adjustable variables
var wrps = 5; // words read per second
var period = 1;
var pointVal = 4; // 4 = toss-up, 10 = bonus
var roundClock;
var buzzerClock;
var endBuzz; // Used to measure time to the end of one question
var textArray = new Array(); // displayText stacks words into a giant waiting line
var roundCount = 120; // seconds per round
var buzzerCount; // 5 for tossup, 20 for bonus
var buzzedIn = false;
var score = 0;

var xhr = null;
var qnum = 0;

var currentQuestion;
var questionStart = false; // has a question begun being asked? Sees if its buzzable

//returns the number of characters which are different from each other
//THIS INIFITE LOOPS AND FREEZES THE PROGRAM.
function getEditDistance(response, solution) {
	//alert("dang does this infinite loop");
	if (response.length <= 0 && solution.length <= 0) return 0;
	if (response == solution) return 0;
	if (response.length <= 0 && solution.length != 0) return solution.length;
	if (response.length != 0 && solution.length <= 0) return response.length;
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

//takes out spaces before and after
function trim(str) {
	while (str.charAt(0) === " ") {
		str = str.substring(1);
	}
	while (str.charAt(str.length-1) === " ") {
		str = str.substring(0, str.length-1);
	}
	return str;
};

//returns true if the response was correct. Tolerance can be added
function checkResponse(response, solution){
	var tolerance = 1; //Increase tolerance to allow for more lenient answers
	if(currentQuestion.format == "Multiple Choice"){
		if(response.toLowerCase().substring(0,1) == solution.toLowerCase().substring(0,1)){ //W X Y or Z
			return true;
		} else if(getEditDistance(response.toLowerCase(), solution.toLowerCase()) < tolerance+3){ //Added tolerance for not including WXYZ
			return true;
		}
	} else { //Short Answer
		if(getEditDistance(response.toLowerCase(), solution.toLowerCase()) < tolerance){
			return true;
		}
	}
	//Answer was incorrect
	return false;
}

//All questions must go through this function whether or not a buzz occured
function retrieveResponse() {
	buzzerCount = 0;
	var response = trim(String($('input[id=response-input]').val()));
	var currentSolution = currentQuestion.solution;
	
	if(buzzedIn){ //Check if there's even an answer to get
		if(checkResponse(response.toLowerCase(), currentSolution.toLowerCase())){
		//if(getEditDistance(response.toLowerCase(), currentSolution.toLowerCase()) < 1){
			score += pointVal;
			document.getElementById("score").innerHTML = score;
			if (pointVal == 4) { //if you got it right, get bonus question
				pointVal = 10;
			} else { //you just got bonus right, get next tossup
				pointVal = 4;
				qnum++;
			}
		} else { //you got it wrong, next tossup
			pointVal = 4;
			qnum++;
		}
	} else { //you didn't even buzz in
		pointVal = 4;
		qnum ++;
	}
	resettoNextQuest();
};

//updates buzzer clock by one second
function buzzerCountDown() {
	if (buzzerCount < 10) {
		document.getElementById("buzzer").innerHTML = "0:0" + buzzerCount.toString();
	} else {
		document.getElementById("buzzer").innerHTML = "0:" + buzzerCount.toString();
	}
	
	if (buzzerCount <= 0) {
		retrieveResponse();
	}
	buzzerCount--;
};

//reset the timers and jumbotron and read the next question
function resettoNextQuest() {
	clearInterval(buzzerClock);
	document.getElementById("question").innerHTML = "GOOD JOB";
	document.getElementById("buzzer").innerHTML = "0:00";
	document.getElementById("form-response").reset();
	$('#response-input').prop('disabled', true);
	buzzedIn = false;
	var node = document.getElementById("question");
	while (node.firstChild) {
		node.removeChild(node.firstChild);
	}
	questionStart = false;
	readQuestion();
}

//updates round clock by one second
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

//Answering the question phase of the problem
function questionResponse() {
	if(pointVal == 10 || buzzedIn){
		buzzedIn = true;
		$('#response-input').prop('disabled', false); // textbox clickable
		$('#response-input').focus(); // textbox contains cursor on space
		clearInterval(buzzerClock);
	} 
	if (pointVal == 4) {
		buzzerCount = 5;
		// Need to add this extra buzzerCountDown to eliminate one second delay
		// between the buzz and the timer starting the countdown. DAVID FORGOT THIS. BLARGH
		buzzerCountDown();
		buzzerClock = setInterval(function() {buzzerCountDown()}, 1000);
	} else {
		buzzerCount = 20;
		buzzerCountDown();
		buzzerClock = setInterval(function() {buzzerCountDown()}, 1000);
	}
};

//Starts the question phase. Called at the beginning of each question phase.
function readQuestion() {
	if (roundCount <= 0 && pointVal == 4) {
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
	var url = "getquestion.php?matchNum=" + qnum + "&pointVal=" + pointVal;
	//alert("url = "+url);
	// get question
	xhr.onreadystatechange = handler;
	xhr.open("GET", url, true);
	xhr.send(null);

	// I'm putting display Tossup and Bonus here so it fills the awk 2 second
	// pause when its ajax calling.
	if (pointVal == 4) {
		displayText("Toss-up", 1500);
	} else {
		displayText("Bonus", 1500);
	}
};

function generateQuests() {
	//alert("HELLO");
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
	var questDifficulty = document.getElementById("questDifficulty").value;
	var questTopics = document.getElementById("questTopics").value;
	var questQuantity = document.getElementById("questQuantity").value;
	//alert("HEYO"+questDifficulty+questTopics+questQuantity);
	var url = "getset.php?questDiff="+questDifficulty+"&questTop="+questTopics+"&questQuant="+questQuantity;
	//alert("url = "+url);
	// get question
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById("question").innerHTML=xhr.responseText;
		}
	}
	xhr.open("GET", url, true);
	xhr.send(null);
};

//Spits out only one word when called
function displayText(addtext, delay, bold) {
	// Keep track of words that are on timer delays so I can delete them
	// if the user interrupts the question.
	var oneWord = setTimeout(function() {
		if (!buzzedIn) {
			var div = document.createElement("div");
			div.className = "qtext";
			var textNode = document.createTextNode(addtext + " ");
			div.appendChild(textNode);
			document.getElementById("question").appendChild(div);
		}
	}, delay);

	textArray.push(oneWord);

};

//goes out and gets the question and information and displays it until a buzz
function handler() {
	// only handle loaded requests
	if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			// evaluate JSON for current question
			// qtopic, qformat, question, solution
			// Convert JSON string to Javascript object
			currentQuestion = eval('(' + xhr.responseText + ')');
			// show JSON in alertbox
			//alert(xhr.responseText);

/*
			if (pointVal == 4) {
				displayText("Toss-up", 0);
			} else {
				displayText("Bonus", 0);
			}
*/		

			displayText(currentQuestion.topic, 1000/wrps);
			displayText(currentQuestion.format, 2*1000/wrps);

			questionStart = true;

			for (var i = 0; i < (currentQuestion.problem).length; i++) {
				displayText((currentQuestion.problem)[i], 3*1000/wrps + i*1000/wrps);
			}

			//Opens answer box automatically at the end of the question if nobody buzzes during the question.
			endBuzz = setTimeout(function() {
				if (!buzzedIn) questionResponse();
				//alert("currentQuestion.question = "+currentQuestion.question);
			}, 3*1000/wrps + (currentQuestion.problem).length * 1000/wrps);

		} else {
			alert("Error with Ajax call!");
		}		
	}
};

function bulkHandler(){
// only handle loaded requests
	if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			// evaluate JSON for current question
			// qtopic, qformat, question, solution
			// Convert JSON string to Javascript object
			currentQuestion = eval('(' + xhr.responseText + ')');
			// show JSON in alertbox
			//alert(xhr.responseText);

/*
			if (pointVal == 4) {
				displayText("Toss-up", 0);
			} else {
				displayText("Bonus", 0);
			}
*/		

			displayText(currentQuestion.topic, 1000/wrps);
			displayText(currentQuestion.format, 2*1000/wrps);

			questionStart = true;

			for (var i = 0; i < (currentQuestion.problem).length; i++) {
				displayText((currentQuestion.problem)[i], 3*1000/wrps + i*1000/wrps);
			}

			//Opens answer box automatically at the end of the question if nobody buzzes during the question.
			endBuzz = setTimeout(function() {
				if (!buzzedIn) questionResponse();
				//alert("currentQuestion.question = "+currentQuestion.question);
			}, 3*1000/wrps + (currentQuestion.problem).length * 1000/wrps);

		} else {
			alert("Error with Ajax call!");
		}		
	}
};

function runGame() {
	//alert("Game has started");
	roundClock = setInterval(function() {roundCountDown()}, 1000);
	document.getElementById("score").innerHTML = score;
	document.getElementById("roundTimer").innerHTML = "0:00";
	document.getElementById("buzzer").innerHTML = "0:00";

	readQuestion();

	//For interrupts and buzzing for tossups whether or not an interrupt.
	//Only works for spacebar (32 is spacebar).
	$('body').keydown(function(e) {
		if (e.keyCode == 32 && !buzzedIn && questionStart){
			//alert("this thing is not updating");
			
			//Clear out all the excess timers after interrupt buzz
			clearTimeout(endBuzz);
			for(var i=0; i<textArray.length; i++){
				clearTimeout(textArray[i]);
			}
			
			buzzedIn = true;
			questionResponse();
		}
	});
};

