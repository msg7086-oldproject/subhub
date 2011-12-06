<?php
if(!file_exists('config.php'))
{
	die(<<<EOT
Put config.php in your website:<br /><hr />
<?php<br />
$host = '';<br />
$username = '';<br />
$passwd = '';<br />
$dbname = '';<br />
$subdomain = '.yourdomain.com';<br />
$maindomain = 'www.yourdomain.com';<br />
$authdomain = 'auth.yourdomain.com';<br />
<br />
EOT
	);
}
	if(empty($_SERVER['PATH_INFO']))
	{
		header('Location: ' . $_SERVER['SCRIPT_NAME'] . '/');
		exit;
	}
	session_start();
	ob_start();
	date_default_timezone_set('Asia/Shanghai');
	if(isset($_SESSION['user']))
		$user = &$_SESSION['user'];
	else
		$user = array();
	$pathinfo = $_SERVER['PATH_INFO'];
	define('BASE', '//' . $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));
	define('SELF', $_SERVER['SCRIPT_NAME'] . str_replace(array('\\', '//'), '/', dirname($pathinfo . 'dummy') . '/'));
	define('SELFROOT', $_SERVER['SCRIPT_NAME']);
	preg_match('~^(?:/(\d+)(?:/(\d+)(?:/(\d+))?)?)?(?:/([^/]*))?~', $pathinfo, $match);

	@list($x, $pid, $eid, $lid, $action) = $match;
	$pid = intval($pid);
	$eid = intval($eid);
	$lid = intval($lid);
	if(empty($action))
		$action = 'index';
	if($pid == 0)
		$action = 'g_' . $action;
	elseif($eid == 0)
		$action = 'p_' . $action;
	elseif($lid == 0)
		$action = 'e_' . $action;
	else
		$action = 'l_' . $action;
	$modfilename = 'modules/' . $action . '.php';
	if(!file_exists($modfilename))
		die('Invalid operation.');

	define('SYS_VER', '1.0');
	require_once 'module.php';
	include $modfilename;

