<?php
require_once 'module.php';
$historys = $db->GetAll('SELECT * FROM `linehistory` LEFT JOIN `users` ON `userid` = `historyauthor` WHERE `lineid` = ? ORDER BY `historytimestamp` ASC', array($lid));
$line = $db->GetRow('SELECT * FROM `lines` WHERE `linestart` > 0 AND `lineid` = ?', array($lid));
$project = $db->GetRow('SELECT * FROM `projects` WHERE `projectid` = ?', array($pid));
$episode = $db->GetRow('SELECT * FROM `episodes` WHERE `episodeid` = ?', array($eid));
if(empty($line['linegoogle']))
{
	$linegoogle = google_trans(str_replace('\N', "\n", $line['linesource']));
	$db->Execute('UPDATE `lines` SET `linegoogle` = ? WHERE `lineid` = ?', array($linegoogle, $lid));
	$line['linegoogle'] = $linegoogle;
}
$tpl->assign('project', $project);
$tpl->assign('episode', $episode);
$tpl->assign('line', $line);
$tpl->assign('historys', $historys);
tpl_display(__FILE__);

function google_trans($str)
{
	$data = file_get_contents('http://translate.googleapis.com/translate_a/t?client=t&text=' . urlencode($str) . '&sl=ja&tl=zh-CN&ie=utf-8&oe=utf-8');
	$data = str_replace(',,', ',0,', $data);
	$data = json_decode($data);
	$transdata = $data[0];
	$result = '';
	foreach($transdata as $d)
	{
		$result .= trim($d[0]) . '\N';
	}
	return $result;
}
