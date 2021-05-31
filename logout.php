<?php 
require('SessionHelper.php');
$session = new SessionHelper();
if($session->isLoggedIn())
{
	$session->logOut();
}
else 
{
	$session->redirect();
}

?>