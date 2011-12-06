<?php
	require_once 'module.php';
	$subtext = $_POST['subtext'];
	if(empty($subtext))
	{
		header('Location: index');
		exit;
	}
	
	$lines = explode("\n", $subtext);
	//Dialogue: 0,0:25:15.16,0:25:17.02,Default,,0000,0000,0000,,ツタ～ジャ！\Nかわせ　ピカチュウ！
	$sub = array();
	foreach($lines as $line)
	{
		if(substr($line, 0, 10) == 'Dialogue: ')
		{
			if(preg_match('~,(\d:\d+:\d+\.\d+),(\d:\d+:\d+\.\d+),[^,]*,[^,]*,\d+,\d+,\d+,[^,]*,(.*)$~', trim($line), $match))
				$sub[] = array(time2int($match[1]), time2int($match[2]), $match[3], $eid);
			else
				$sub[] = array(-1, -1, trim($line), $eid);
		}
		else
			$sub[] = array(-1, -1, trim($line), $eid);
	}
	
	$db->Execute('INSERT INTO `lines` (`linestart`, `lineend`, `linesource`, `episodeid`) VALUES (?, ?, ?, ?)', $sub);
	header('Location: index');
	