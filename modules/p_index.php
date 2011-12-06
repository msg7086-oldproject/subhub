<?php
require_once 'module.php';
$project = $db->GetRow('SELECT * FROM `projects` WHERE `projectid` = ?', array($pid));
$episodes = $db->GetAll('SELECT * FROM `episodes` WHERE `projectid` = ?', array($pid));
$tpl->assign('project', $project);
$tpl->assign('episodes', $episodes);
tpl_display(__FILE__);
