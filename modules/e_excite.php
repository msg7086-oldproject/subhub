<?php
require_once 'module.php';
$lines = $db->GetAll('SELECT * FROM `lines` WHERE `episodeid` = ? AND `linestart` > 0', array($eid));
$text = '';
foreach($lines as $line)
	$text .= '!' . $line['lineid'] . '!' . $line['linesource'] . EOL . EOL;

$trans = excite_trans($text);
preg_match_all('~^!(\d+)!(.*)$~m', $trans, $matches, PREG_SET_ORDER);
$dataarray = array();
foreach($matches as $match)
	$dataarray[] = array($match[2], $match[1]);
if(count($dataarray) > 0)
	$db->Execute('UPDATE `lines` SET `linegoogle` = ? WHERE `lineid` = ?', $dataarray);

die('Written successfully!');

function excite_trans($str)
{
	$cu = curl_init('http://www.excite.co.jp/world/jiantizi/');
	curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cu, CURLOPT_POSTFIELDS, array(
		'before' => $str,
		'wb_lp' => 'JACH',
		'big5' => 'no'
	));
	$result = curl_exec($cu);
	preg_match('~<textarea name="after" id="after">(.*)</textarea>~s', $result, $match);
	return $match[1];
}
