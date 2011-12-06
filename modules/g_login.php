<?php
$pass = $_POST['pass'];
$source = $_SERVER['HTTP_REFERER'];
if(empty($pass))
{
	header('Location: ' . $source);
	exit;
}

$userdata = $db->GetRow('SELECT * FROM `users` WHERE `password` = ?', md5($pass));
if(!$userdata)
{
	header('Location: ' . $source);
	exit;
}

$_SESSION['user'] = $userdata;
header('Location: ' . $source);
