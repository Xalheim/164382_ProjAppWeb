function getTodaysDate() {
	today = new Date();
	thedate = "" + (today.getMonth() + 1) + " / " + today.getDate() + " / " + (today.getYear()-100);
	document.getElementById("data").innerHTML = thedate;
}

var timerid = null;
var timerrunning = false;

function stopClock() {
	if(timerrunning) {
		clearTimeout(timerid);
	}
	timerrunning=false;
}

function startClock() {
	stopClock();
	getTodaysDate();
	showTime();
}

function showTime() {
	var now = new Date();
	var hours = now.getHours();
	var minutes = now.getMinutes();
	var seconds = now.getSeconds();
	var timevalue = "" + ((hours > 12) ? hours - 12 : hours)
	timevalue += ((minutes < 10) ? ":0" : ":") + minutes
	timevalue += ((seconds < 10) ? ":0" : ":") + seconds
	timevalue += ((hours >= 12) ? " P.M." : " A.M.")
	document.getElementById("zegarek").innerHTML = timevalue;
	timerid = setTimeout("showTime()", 1000);
	timerrunning = true;
}
