var userinfo;
var latency = 0;
var config = getJSON("config.json", function(data){
	config = JSON.parse(data);
});

function getJSON(path, ret)
{ 
  var file = new XMLHttpRequest();
  file.overrideMimeType("application/json");
  file.open('GET', path, true);
  file.onreadystatechange = function () {
	if (file.readyState === 4 && file.status == "200") {
	  ret(file.responseText);
	}
	
  };
  file.send(null); 
}
	
window.onload = function()
{
	var serverInfo = config.serverConfig;
	mainSocket = new WebSocket("ws://" + serverInfo.serverIP + ":" + serverInfo.serverPort);
	var readyBtn = document.getElementById('readyup');
	var img = document.getElementById('clickme');
	var points = 0;
	var timeout;
	var heartbeat;
	var otherPlayerScore = document.getElementsByClassName('scorecount')[1].firstElementChild.innerText;
	
	readyBtn.addEventListener('click', function(){
		mainSocket.send("ready");
	}, false);
	
	mainSocket.onopen = function(opening)
	{
		userinfo = new User(document.getElementById('id').innerText, document.getElementById('username').innerText, document.getElementById('rank').innerText); //CLEAN THIS UP
		console.log(userinfo);
		mainSocket.send(constructMsg('connection', userinfo));
		heartbeat = setInterval(function(){
			try {
			mainSocket.send(constructMsg("heartbeat", "ping"));
			}
			catch (e)
			{
				clearInterval(heartbeat);
			}
		}, 2000);
		checkStatus();
	}
	
	mainSocket.onclose = function(closing)
	{
		clearTimeout(timeout);
		clearInterval(heartbeat);
		if (closing.code == 1006)
		{
			console.log("Server unavailable");
		}
		else 
		{
			console.log('Connection lost');
		}
	}
	
	
	mainSocket.onmessage = function(message)
	{
		if (isJSON(message.data)) //Generic statement, however will help to identify position parameters - eventually JSON data will include request/message type.
		{
			data = JSON.parse(message.data)
			console.log(data);
			if (data.messageType == "heartbeat")
			{
				console.log("Received a ping: %s ms.", (Date.now() - latency)/2);
				checkStatus();
			}
			else if (data.messageType == "accepted")
			{
				userinfo.setSID(data.contents);
				console.log(data.contents);
				console.log(userinfo);
			}
			else if (data.messageType == "position")
			{
				changeImageLocation(data.contents[0], data.contents[1]);
			}
			else if (data.messageType == "info")
			{
				console.log("ID: %s", data.contents);
			}
		}
		if (message.data == "go")
		{
			readyBtn.style.display = "none";
			img.style.display = "block";
			img.style.position = "absolute";
		}
		else if (message.data == "change")
		{
			img.style.display = "block";
		}
		else if (message.data == "hide")
		{
			img.style.display = "none";
		}
		else if (message.data == "retry")
		{
			readyBtn.style.display = "block";
			img.style.display = "none";
			points = 0;
			otherPlayerScore = 0;
		}
		else if (message.data == "increasePlayerScore")
		{
			points++;
			if (points == 5)
			{
				mainSockdet.send("end");
				document.getElementsByClassName('scorecount')[0].innerText = "Player 1 has won";
			}
			document.getElementsByClassName('scorecount')[0].firstElementChild.innerText = points;
		}
		else if (message.data == "increaseScore")
		{
			otherPlayerScore++;
			if (otherPlayerScore != 5)
			{
				document.getElementsByClassName('scorecount')[1].firstElementChild.innerText = otherPlayerScore;
			}
			else 
			{
				document.getElementsByClassName('scorecount')[0].innerText = "Player 2 has won";
			}
		}
	}
	
	
	img.addEventListener('click', function(){
		mainSocket.send("clicked");
	}, false);
	
	function canConnect() 
	{
		try 
		{
			document.getElementById('server-status').innerText += " UP";
		}
		catch (e)
		{
			document.getElementById('server-status').innerText += " DOWN";
			return false;
		}
		return true;
	}
	
	function changeImageLocation(rndX, rndY)
	{
		img.style.top = rndY + "px";
		img.style.left = rndX + "px";
	}
	
	function checkStatus()
	{
		console.log("Checking status");
		clearTimeout(timeout);
		
		
		timeout = setTimeout(function(){
			mainSocket.close();
			clearInterval(heartbeat);
		}, 30000);
	}
	
}

function isJSON(data) {
	try {
		JSON.parse(data);
	} 
	catch (e){
		return false;
	}
	return true;
}

function constructMsg(_msgType, _contents) {
	latency = Date.now();
	if (Array.isArray(_contents))
	{
		JSON.stringify(_contents);
	}
	var message = {
		messageType: _msgType,
		timestamp: latency,
		contents: _contents
	}
	console.log(message);
	return JSON.stringify(message);
}