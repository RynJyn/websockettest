const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8080 });

var playerCount = 0;

wss.on('connection', function connection(ws) {
  ws.on('message', function incoming(message) {
    console.log('received: %s', message);
	
	if (message == "ready")
	{
		playerCount++;
		console.log("Players: %s", playerCount);
		if (playerCount == 2)
		{
			console.log("Enough players");
			var maxX = Math.floor((Math.random() * 1500) + 1);
			var maxY = Math.floor((Math.random() * 900) + 1);
			wss.clients.forEach(function each(client)
			{
				client.send(JSON.stringify([maxX, maxY]));
				client.send("go");
			});
		}
	}
	else if (message == "clicked")
	{
		wss.clients.forEach(function each(client)
			{
			client.send("hide");
			client == ws ? client.send("increasePlayerScore") : client.send("increaseScore")
			});
		
		var maxX = Math.floor((Math.random() * 1500) + 1);
		var maxY = Math.floor((Math.random() * 900) + 1);
		
		var timeout = Math.floor((Math.random() * 5000) + 1);
		setTimeout(function(){
			wss.clients.forEach(function each(client)
			{
				client.send(JSON.stringify([maxX, maxY]));
				client.send("change");
			});
			}, timeout);
	}
	else if (message == "end")
	{
		wss.clients.forEach(function each(client)
			{
			client.send("retry");
			});
		playerCount = 0;
	}
  });
});

wss.on('close', function closecon(ws){
	playerCount--;
});