<?php 
class SessionHelper 
{
	private static $loginPage = 'login.php';
	
	function __construct()
	{
		session_start();
	}
	function isLoggedIn()
	{
		return isSet($_SESSION['userid']);
	}
	
	function logOut()
	{
		$_SESSION['userid'] = null;
		$_SESSION['rank'] = null;
		$_SESSION['username'] = null;
		session_destroy();
		header('Location: login.php');
	}
	
	function redirect()
	{
		header('Location: '.self::$loginPage);
	}
	
	function getUserRank()
	{
		return $_SESSION['rank'];
	}
	
	function getUserName()
	{
		return $_SESSION['username'];
	}
	
	function getUserID()
	{
		return $_SESSION['userid'];
	}
	
	function isValidated()
	{
		if (isSet($_SESSION['isValidated']) && $_SESSION['isValidated'] == 0)
		{
			return false;
		}
		return true;
	}
}
?>