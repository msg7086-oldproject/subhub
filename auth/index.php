<?php
require '../config.php';
function WriteCookie($key)
{
	global $subdomain;
	setcookie($key, $_SERVER[$key], time() + 86400, '', $subdomain, true, true);
}
WriteCookie('SSL_CLIENT_S_DN_CN');
WriteCookie('SSL_CLIENT_I_DN_CN');
WriteCookie('SSL_CLIENT_VERIFY');
setcookie('SSL_AUTH_SALT', md5($_SERVER['SSL_CLIENT_S_DN_CN'] . number_format(time() / 100) . '@auth'), time() + 100, '', $subdomain, true, true);
if(isset($_SERVER['HTTP_REFERER']))
	header('Location: ' . $_SERVER['HTTP_REFERER']);
else
	header('Location: ' . 'https://' . $maindomain);
