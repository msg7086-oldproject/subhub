{{include file="header.tpl"}}
<div>
<h1><a href="{{$SELFROOT}}/">索引</a> - <a href="{{$SELF}}../../">{{$project.projectname}}</a> - <a href="{{$SELF}}../">{{$episode.episodename}}</a> - <a href="{{$SELF}}" class="jp">{{$line.linesource}}</a></h1><br />
<form method="post" action="{{$SELF}}put">
<table width="100%">
<colgroup>
	<col width="10%" />
	<col width="70%" />
	<col width="20%" />
</colgroup>
	<tr>
		<td rowspan="2">{{$line.lineid}}</td>
		<td class="jp">{{$line.linesource|n2br}}</td>
		<td>{{$line.linestart|time2str}}</td>
	</tr>
	<tr>
		<td>{{$line.linegoogle|n2br}}</td>
		<td>{{$line.lineend|time2str}}</td>
	</tr>
{{if $historys neq false}}
{{foreach from=$historys item=his}}
	<tr>
		<td>#{{$his.historyid}}</td>
		<td>{{$his.historytrans|n2br}}</td>
		<td>{{$his.historytimestamp|mydate}}<br />{{$his.historyauthor}} {{$his.username}}</td>
	</tr>
{{/foreach}}
{{/if}}
{{if isset($user.username)}}
	<tr>
		<td>{{$SESSION.username}}</td>
		<td><input type="text" class="w" name="translation" value="" /></td>
		<td><input type="submit" value="提交翻译" /></td>
	</tr>
{{else}}
	<tr>
		<td colspan="3">登录后可提交翻译</td>
	</tr>
{{/if}}
</table>
</form>
</div>
{{include file="footer.tpl"}}
