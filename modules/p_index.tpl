{{include file="header.tpl"}}
<div id="p">
<h1><a href="{{$SELFROOT}}/">索引</a> - <a href="{{$SELF}}">{{$project.projectname}}</a></h1><br />
<ul>
{{foreach from=$episodes item=ep}}
	<li><a href="{{$SELF}}{{$ep.episodeid}}/"><span class="big">{{$ep.episodename}}</span> <span class="jp">{{$ep.episodecomment}}</span> ({{$ep.episodedate|mydate:'Y-m-d'}})</a>
	</li>
{{/foreach}}
</ul>
</div>
{{include file="footer.tpl"}}
