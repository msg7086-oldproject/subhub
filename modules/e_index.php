<?php
require_once 'module.php';
$lines = $db->GetAll('
	SELECT `lines`.*, u1.*, u2.username rusername
	FROM `lines`
	LEFT JOIN `users` u1
	ON u1.userid = lines.lineauthor
	LEFT JOIN `users` u2
	ON u2.userid = lines.linerauthor
	WHERE `episodeid` = ?', array($eid));
$project = $db->GetRow('SELECT * FROM `projects` WHERE `projectid` = ?', array($pid));
$episode = $db->GetRow('SELECT * FROM `episodes` WHERE `episodeid` = ?', array($eid));
$tpl->assign('project', $project);
$tpl->assign('episode', $episode);
$tpl->assign('lines', $lines);
tpl_display(__FILE__);
