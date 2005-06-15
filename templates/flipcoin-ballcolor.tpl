{include file="header.tpl" title=$title}

<form action="" method="post">
<div style="padding-top: 1em; padding-left: 1em;">{$chose_balls_name} chose to use the following balls:</div>
<div style="padding-left: 6em;">
{section name=ball loop=$prev_balls}
{$prev_balls[ball].name}: {$prev_balls[ball].color}<br />
{/section}
</div>
<div id="labels">
<div class="label">
{include file="choseballs.tpl"}
</div>
<div style="text-align: right; width: 24em; padding-top: 0px;">
<input name="submit" type="submit" value="Choose Balls" />
</div>
</div>
</form>


{include file="footer.tpl"}
