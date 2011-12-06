{{include file="header.tpl"}}
<div>
<h1><a href="{{$SELFROOT}}/">索引</a> - <a href="{{$SELF}}../">{{$project.projectname}}</a> - <a href="{{$SELF}}">{{$episode.episodename}}</a></h1><br />
{{if $lines neq false}}
<span style="float: right">
  <a href="{{$SELF}}export">导出时间轴</a>
  <a href="{{$SELF}}export?cnonly=1">导出纯中文轴</a>
  <a href="{{$SELF}}export?see=1">看时间轴</a>
  <a href="{{$SELF}}merge">将本地时间轴合并到线上</a>
  </span>
<form action="{{$SELF}}reserve" method="post">
<table width="100%">
<colgroup>
	<col width="90" />
	<col width="90" />
	<col width="30%" />
	<col width="25%" />
	<col width="150" />
	<col width="150" />
</colgroup>
{{foreach from=$lines item=l}}
{{if $l.linestart eq -1}}
	<tr class="meta">
		<td class="small" colspan="6">{{$l.linesource|n2br}}</td>
	</tr>
{{else}}
	<tr>
		<td class="small">{{$l.linestart|time2str}}</td>
		<td class="small">{{$l.lineend|time2str}}</td>
		<td class="jp">{{$l.linesource|n2br}}</td>
		<td class="hovercontainer"><a href="{{$SELF}}{{$l.lineid}}/">{{$l.linetrans}}</a><button id="edit_l{{$l.lineid}}0" class="hiddenhover btn_edit"></button></td>
		<td>{{$l.linetimestamp|mydate}}</td>
		<td>{{if $l.username}}{{$l.username}}{{/if}}
		{{*if isset($user.username)}}
			{{if $l.linertimestamp lt 0|time}}<label><input type="checkbox" name="reserve[]" value="{{$l.lineid}}" />预约</label>
			{{else}}
				由{{$l.rusername}}<br />预约至{{$l.linertimestamp|mydate:'H:i'}}
			{{/if}}
		{{/if*}}
		</td>
	</tr>
{{/if}}
{{/foreach}}
</table>
{{if isset($user.username)}}
	<input type="submit" value="预约" />
{{/if}}
</form>
{{else}}
{{if isset($user.username)}}
<form method="post" action="{{$SELF}}import">
	<textarea name="subtext"></textarea>
	<br />
	<input type="submit" value="Submit" />
</form>
{{/if}}
{{/if}}
</div>
{{include file="footer.tpl"}}
