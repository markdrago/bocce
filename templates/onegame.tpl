{include file="header.tpl" title=$title}

<h2>{$gamedate}</h2>
<br />

<h3>Score History</h3>
<table class="scoreboard">
<tr><th>Round</th>
{section name=mysec loop=$scoredata}
{strip}
<th>{$scoredata[mysec].round}</th>
{/strip}
{/section}
</tr>

<tr><th>{$winner_name}</th>
{section name=mysec loop=$scoredata}
{strip}
<td>{$scoredata[mysec].winner_pts}</td>
{/strip}
{/section}
</tr>

<tr><th>{$loser_name}</th>
{section name=mysec loop=$scoredata}
{strip}
<td>{$scoredata[mysec].loser_pts}</td>
{/strip}
{/section}
</tr>
</table>

<h3>Bruises</h3>
<table class="bruises">
<tr><th>Name</th><th>Round</th><th>Success</th></tr>
{section name=mysec loop=$bruisedata}
{strip}
<tr>
<td>{$bruisedata[mysec].name}</td><td>{$bruisedata[mysec].round}</td><td>{$bruisedata[mysec].success}</td>
</tr>
{/strip}
{/section}
</table>
{include file="footer.tpl"}
