<?php
require_once 'module.php';
$subtext = isset($_POST['subtext']) ? $_POST['subtext'] : '';
if(!empty($subtext))
{
	$dblines = $db->GetAssoc('
		SELECT `lines`.*, u1.*, u2.username rusername
		FROM `lines`
		LEFT JOIN `users` u1
		ON u1.userid = lines.lineauthor
		LEFT JOIN `users` u2
		ON u2.userid = lines.linerauthor
		WHERE `linestart` > 0
		AND `episodeid` = ?', array($eid));

	$lines = explode("\n", $subtext);
	//Dialogue: 0,0:25:15.16,0:25:17.02,Default,,0000,0000,0000,,ツタ～ジャ！\Nかわせ　ピカチュウ！
	$sub = array();
	$lost = '';
	$skipc = 0, $writec = 0;
	foreach($lines as $line)
	{
		if(strlen($line) == 0 || $line[0] == ';')
			continue;
		if(strpos($line, 'DefaultCN') !== false)
		{
			if(preg_match('~,(\d:\d+:\d+\.\d+),(\d:\d+:\d+\.\d+),DefaultCN,(\d+)_([^_]{8,8})[^,]*,\d+,\d+,\d+,[^,]*,(.*)$~', trim($line), $match))
			{
				$start = $match[1];
				$end = $match[2];
				$lid = $match[3];
				$crc = $match[4];
				$text = $match[5];
				if(sprintf("%08X", crc32($text)) == $crc)
				{
					$skipc++;
					continue; // skip if crc not changed
				}
				if(substr($text, 0, 3) == '-G-')
				{
					$skipc++;
					continue; // skip if it's google translation
				}
				if(!isset($dblines[$lid]))
				{
					$lost .= '; Invalid Episode ID' . EOL;
					$lost .= trim($line) . EOL;
				}
				$dbline = $dblines[$lid];
				if($dbline['linetrans'] == $text)
				{
					$skipc++;
					continue; // skip if duplicated submit
				}
				if(sprintf("%08X", crc32($dbline['linetrans'])) == $crc || $line[0] == '!')
				{
					// update old data to new one
					$db->Execute('INSERT INTO `linehistory` (`historytrans`, `historytimestamp`, `historyauthor`, `lineid`) VALUES (?, ?, ?, ?)', array($text, time(), $user['userid'], $lid));
					$db->Execute('UPDATE `lines` SET `linetrans` = ?, `linetimestamp` = ?, `lineauthor` = ? WHERE `lineid` = ?', array($text, time(), $user['userid'], $lid));
					if($dbline['linerauthor'] == $user['userid'])
					{
						// release lock if user has reserved this line
						$db->Execute('UPDATE `lines` SET `linertimestamp` = 0, `linerauthor` = 0 WHERE `linerauthor` = ? AND `lineid` = ?', array($user['userid'], $lid));
					}
					if($dbline['linestart'] != time2int($start) || $dbline['lineend'] != time2int($end))
					{
						$db->Execute('UPDATE `lines` SET `linestart` = ?, `lineend` = ? WHERE `lineid` = ?', array(time2int($start), time2int($end), $lid));
					}
					writec++;
					continue;
				}
				else
				{
					// conflict! inform user to put a ! before Dialogue tag
					$lost .= sprintf('; Conflict: %s write %s on %s for %s', $dbline['username'], $dbline['linetrans'], mydate($dbline['linetimestamp']), $dbline['linesource']) . EOL;
					$lost .= '; Merge your translation and put `!\' before `Dialogue\'' . EOL;
					$lost .= trim($line) . EOL;
				}
			}
			else
			{
				$lost .= '; Invalid Format' . EOL;
				$lost .= trim($line) . EOL;
			}
		}
	}
	$lost .= sprintf('; Write: %d\tSkip: %d', $writec, $skipc) . EOL;
}
else
	$lost = ' ';
if(empty($lost))
{
	header('Location: ./');
	exit;
}
$tpl->assign('lost', $lost);
tpl_display(__FILE__);
