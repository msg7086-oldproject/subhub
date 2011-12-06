<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<base href="{{$smarty.const.BASE}}" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Sub Mirai</title>
	<link href="stylesheet.css" type="text/css" rel="stylesheet" />
</head>

<body>
	<div id="main">
{{if isset($user.username)}}
		{{$user.username}}
{{else}}
		<a href="https://{{$authdomain}}">Login</a>
		<form action="{{$SELFROOT}}/login" method="post">
			<div id="loginarea">
				Password: <input type="password" name="pass" value="" />
				<input type="submit" value="Login" />
			</div>
		</form>
{{/if}}
