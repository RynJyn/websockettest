<?php 
session_start();
if (!isSet($_SESSION['userid']))
{
	
	
?>

<!DOCTYPE html>
<html>
<head>
<title>Create An Account</title>
<link rel="stylesheet" type="text/css" href="css/signup.css"/>
</head>

<body>
<main class="flex">
	<div id="sign-up-box">
		<h1>Create your account:</h1>
		<form name="register" method="POST" action="register.php">
			<input name="username" type="text" autocomplete="off" autofocus placeholder="Username" required></input>
			<input name="password" type="password" autocomplete="off" placeholder="Password" required></input>
			<input name="confirm" type="password" autocomplete="off" placeholder="Confirm Password" required></input>
			<input name="email" type="text" autocomplete="off" placeholder="Email" required></input>
			<input type="submit" value="Create Account"></input>
		</form>
		<?php 
		if (isSet($_GET['m']))
		{
			$msg = $_GET['m'];
			if ($msg == "1")
			{
				echo '<p>Account creation successful! Click <a href="login.php">here</a> to log in.</p>';
			}
			else if ($msg == "2")
			{
				echo '<p>There was an error creating your account. Try again.</p>';
			}
			else if ($msg == "3")
			{
				echo '<p>Your passwords did not match. Please try again.</p>';
			}
		}
		?>
	</div>
</main>
</body>
</html>

<?php 
}
else 
{
	header('Location: index.php');
}
?>