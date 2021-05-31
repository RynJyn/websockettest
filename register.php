<?php 
if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if (isSet($_POST['username']) && isSet($_POST['password']) && isSet($_POST['confirm']) && isSet($_POST['email']))
	{
		if($_POST['password'] != $_POST['confirm'])
		{
			header('Location: create-account.php?m=3');
		}
		else
		{
			session_start();
			date_default_timezone_set('UTC');
			
			require("dbconfig.php");
			if($isDBConnected == true)
			{
				$pswd = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$stmt = $pdo->prepare("INSERT INTO users (userID, username, password, rank, creationDate) values (:id, :username, :password, :rank ,:creationDate)");
				$stmt->execute(['id' => mt_random(1, 9999), 'username' => $_POST['username'], 'password' => $pswd, 'rank' => 1, 'creationDate' => date("Y-m-d")]);
				if ($stmt->rowCount() > 0)
				{
					header('Location: create-account.php?m=1');
				}
				else
				{
					header('Location: create-account.php?m=2');
				}
			}
		}
	}
}

?>