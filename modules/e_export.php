<?php
require_once 'module.php';
if(!isset($user['username']))
{
	header('Location: ' . $source);
	exit;
}

$lines = $db->GetAll('SELECT * FROM `lines` LEFT JOIN `users` ON userid = linerauthor WHERE `episodeid` = ? ORDER BY `linestart` ASC, `lineid` ASC', array($eid));
$project = $db->GetRow('SELECT * FROM `projects` WHERE `projectid` = ?', array($pid));
$episode = $db->GetRow('SELECT * FROM `episodes` WHERE `episodeid` = ?', array($eid));

$cnonly = isset($_GET['cnonly']);

if(isset($_GET['see']))
{
	header(sprintf('Content-Disposition: inline; filename="%s %s %s.ass"', $project['projectname'], $episode['episodename'], $episode['episodecomment']));
	header('Content-Type: text/plain; charset=utf8');
}
else
{
	echo "\xEF\xBB\xBF";
	header(sprintf('Content-Disposition: attachment; filename="%s %s %s.ass"', $project['projectname'], $episode['episodename'], $episode['episodecomment']));
	header('Content-Type: application/octet-stream');
}

	//Dialogue: 0,0:25:15.16,0:25:17.02,Default,,0000,0000,0000,,ツタ～ジャ！\Nかわせ　ピカチュウ！
foreach($lines as $line)
{
	if($line['linestart'] < 0)
	{
		if($line['linesource'] == '[Events]')
		{
			echo 'Style: DefaultJP,MS PGothic,36,&H00FFFFFF,&H000000FF,&H00000000,&H00000000,0,0,0,0,100,100,0,0,1,1,1,2,10,10,65,1', EOL;
			echo 'Style: DefaultCN,simhei,36,&H00FFFFFF,&H000000FF,&H00000000,&H00000000,0,0,0,0,100,100,0,0,1,1,1,2,10,10,20,1', EOL, EOL;
		}
		echo $line['linesource'], EOL;
	}
	else
	{
		$text = empty($line['linetrans']) ? '-G-' . $line['linegoogle'] : $line['linetrans'];
		$sign = crc32($text);
		$extra = sprintf('%d_%08X', $line['lineid'], $sign);
		if($line['linertimestamp'] > time())
			$extra .= '_R' . $line['username'] . 'R';
		
		if($text == '-')
			echo ';';
		printf('Dialogue: 0,%s,%s,DefaultCN,%s,0000,0000,0000,,%s', time2str($line['linestart']), time2str($line['lineend']), $extra, $text);
		echo EOL;
		if(!$cnonly)
		{
			printf('Dialogue: 0,%s,%s,DefaultJP,JPSource,0000,0000,0000,,%s', time2str($line['linestart']), time2str($line['lineend']), $line['linesource']);
			echo EOL;
		}
	}
	
}
