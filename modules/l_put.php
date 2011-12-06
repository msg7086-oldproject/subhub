<?php
	require_once 'module.php';
	$source = $_SERVER['HTTP_REFERER'];
	if(!isset($user['username']))
	{
		header('Location: ' . $source);
		exit;
	}
	
	$translation = $_POST['translation'];
	if(empty($translation))
	{
		header('Location: ' . $source);
		exit;
	}
	
	$db->Execute('INSERT INTO `linehistory` (`historytrans`, `historytimestamp`, `historyauthor`, `lineid`) VALUES (?, ?, ?, ?)', array($translation, time(), $user['userid'], $lid));
	$db->Execute('UPDATE `lines` SET `linetrans` = ?, `linetimestamp` = ?, `lineauthor` = ? WHERE `lineid` = ?', array($translation, time(), $user['userid'], $lid));
	header('Location: ' . $source);
	