<?php 
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	//Data is most likely coming from a form
	if(isSet($_POST['username']) && isSet($_POST['password']))
	{
		session_start();
		require("dbconfig.php");
		
		if(isSet($_POST['method']))
		{
			$isAjax = true;
		}
		
		if ($isDBConnected == true)
		{
			$stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(username)=:username");
			$stmt->execute(['username' => strtolower($_POST['username'])]);
			$result = $stmt->fetch();
			if (!empty($result))
			{
				if (password_verify($_POST['password'], $result['password']))
				{
					$_SESSION['userid'] = $result['userID'];
					$_SESSION['username'] = $result['username'];
					$_SESSION['rank'] = $result['rank'];
					header(($isAjax ? 'Content-type: application/JSON' : 'Location: index.php'));
					echo 'success';
				}
				else 
				{
					header(($isAjax ? 'Content-type: application/JSON' : 'Location: login.php?m=3'));
					echo 'Incorrect password.';
				}
			}
			else 
			{
				header(($isAjax ? 'Content-type: application/JSON' : 'Location: login.php?m=1'));
				echo 'User does not exist.';
			}
		}
		else 
		{
			header(($isAjax ? 'Content-type: application/JSON' : 'Location: login.php?m=2'));
			echo 'There was an error connecting to the server. Please try again later.';
		}
		
	}
}
else 
{
	header('Location: login.php');
	//User doesn't need to access this page. Send them packing.
}

?>