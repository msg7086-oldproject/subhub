<?php
if(!defined('SYS_VER'))
{
	header('HTTP/1.1 404 Not Found');
	exit;
}
require 'adodb5/adodb.inc.php';
require 'smarty/Smarty.class.php';
$driver = 'mysql';
$db = &ADONewConnection($driver);
require 'config.php';

$db->Connect($host, $username, $passwd, $dbname);
$db->query('SET NAMES UTF8;');

if(isset($_COOKIE['SSL_AUTH_SALT']))
{
	$salt = $_COOKIE['SSL_AUTH_SALT'];
	$username = isset($_COOKIE['SSL_CLIENT_S_DN_CN']) ? $_COOKIE['SSL_CLIENT_S_DN_CN'] : '';
	if($username && md5($username . number_format(time() / 100) . '@auth') == $salt)
	{
		setcookie('SSL_AUTH_SALT', 'deleted', time() - 864000, '', $subdomain);
		$userdata = $db->GetRow('SELECT * FROM `users` WHERE `username` = ?', $username);
		if(!$userdata)
			setcookie('SSL_CLIENT_VERIFY', 'deleted', time() - 864000, '', $subdomain);
		$user = $_SESSION['user'] = $userdata;
	}
}

$tpl = new Smarty();
$tpl->debugging = false;
$tpl->left_delimiter = '{{';
$tpl->right_delimiter = '}}';
$tpl->template_dir = '/';
$tpl->compile_dir = 'temp/';
$tpl->cache_dir = 'temp/';
$tpl->compile_check = true;
$tpl->caching = false;
$tpl->assign(array(
	'SELF' => SELF,
	'SELFROOT' => SELFROOT,
	'BASE' => BASE,
	'user' => $user,
	'authdomain' => $authdomain,
));

$source = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index';
define('EOL', "\r\n");


function tpl_display($str)
{
	global $tpl;
	$tpl->display(str_replace('.php', '.tpl', $str));
}

function time2int($str)
{
	$hour = substr($str, 0, 1);
	$min = substr($str, 2, 2);
	$sec = substr($str, 5, 2);
	$hsec = substr($str, 8, 2);
	return $hour * 360000 + $min * 6000 + $sec * 100 + $hsec;
}

function time2str($t)
{
	return sprintf('%d:%02d:%02d.%02d', $t / 360000, $t % 360000 / 6000, $t % 6000 / 100, $t % 100);
}

function n2br($str)
{
	return str_replace('\N', '<br />', $str);
}

function mydate($time, $str = 'Y-m-d H:i')
{
	if($time == 0)
		return 'Unmodified';
	$duration = time() - $time;
	$day = floor($duration / 86400);
	$datestr = '';
	if($day > 365)
		$datestr = number_format($day / 365.24, 2) . '年前';
	elseif($day > 30)
		$datestr = number_format($day / 30.4, 2) . '月前';
	elseif($day > 1)
		$datestr = number_format($duration / 86400, 2) . '天前';
	elseif($duration > 3600)
		$datestr = number_format($duration / 3600, 2) . '小时前';
	elseif($duration > 60)
		$datestr = number_format($duration / 60, 2) . '分钟前';
	else
		$datestr = '刚刚';
	
	return '<abbr title="' . date($str, $time) . '">' . $datestr . '</abbr>';
}
