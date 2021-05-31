<?php 
require ('SessionHelper.php');
$session = new SessionHelper();
if(!$session->isLoggedIn())
{
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" href="css/login.css"/>
<script type="text/javascript">
function getJSON(path, ret, args)
{
	var file = new XMLHttpRequest();
	file.overrideMimeType("application/json");
	file.open('POST', path, true);
	file.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	file.onreadystatechange = function () {
		if (file.readyState === 4 && file.status == "200") {
			ret(file.responseText);
		}
	};
	file.send("username=" + args[0] + "&password=" + args[1] + "&method=ajax"); 
}  
	
	window.onload = function()
	{
		document.getElementById('login-form').addEventListener('submit', function(e){
			e.preventDefault();
			getJSON('check-login.php', function(data){
				if (data != "success")
				{
					displayError(data);
				}
				else 
				{
					window.location = "index.php";
				}
			}, [document.getElementById('username').value, document.getElementById('password').value]);
		}, false);
		
		function displayError(error)
		{
			document.getElementById('error').style.display="block";
			document.getElementById('error').innerText = error;
		}
	}
</script>

</head>

<body>
<main class="flex">
	<div id="login-box">
		<h1>Login</h1>
		<form id="login-form" name="login-form" method="POST" action="check-login.php">
		<input id="username" name="username" autocomplete="off" autofocus placeholder="Username" type="text" required value=""></input>
		<input id="password" name="password" placeholder="Password" type="password" required value=""></input>
		<input id="play" value="Play" type="submit"></input>
		</form>
		
		
		<?php 
		if(isSet($_GET['m']))
		{
			switch ($_GET['m'])
			{
				case 1:
					echo "<p>Username doesn't exist. Please try again.</p>";
					break;
				case 2:
					echo "<p>There was an error connecting to the server. Please try again later</p>";
					break;
				case 3:
					echo "<p>Password incorrect. Please try again.</p>";
					break;
				case 4:
					echo "<p>You have been logged out successfully.</p>";
					break;
			}
		}
		else 
		{
			echo '<p id="error" style="display: none;"></p>';
		}
		?>
		
		<p>Don't have an account? <a href="create-account.php">Sign up now!</a></p>
	</div>
</main>
</body>
</html>

<?php 
}
else {
	$session->redirect();
}