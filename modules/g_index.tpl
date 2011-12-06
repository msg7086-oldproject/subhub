{{include file="header.tpl"}}
<div>
<h1><a href="{{$SELFROOT}}/">索引</a></h1>
<ul>
{{foreach from=$projects item=proj}}
<li><h2><a href="{{$SELF}}{{$proj.projectid}}/">{{$proj.projectname}}</a></h2></li>
{{/foreach}}
</ul>
</div>
{{include file="footer.tpl"}}
