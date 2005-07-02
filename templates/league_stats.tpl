{include file="header.tpl" title=$title}

<br />

<table>
<tr><th>Rank</th><th>League Name</th><th>Score</th></tr>
{section name=l loop=$leagues}
<tr><td>{$leagues[l].rank}</td><td><a href="statistics.php?league={$leagues[l].id}">{$leagues[l].name}</a></td><td>{$leagues[l].score}</td></tr>
{/section}
</table>

{include file="footer.tpl"}
