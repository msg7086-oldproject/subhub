{{include file="header.tpl"}}
<div>
<form method="post" action="{{$SELF}}merge">
	<textarea name="subtext">{{$lost}}</textarea>
	<br />
	<input type="submit" value="Submit" />
</form>
</div>
{{include file="footer.tpl"}}
