{include file="header.tpl" title=$title}

<table class="allgames">
<tr><th>Winner</th><th>Loser</th><th>Score</th><th>Date &amp; Time</th></tr>
{section name=mysec loop=$data}
{strip}
<tr><td>{$data[mysec].winner_name}</td><td>{$data[mysec].loser_name}</td><td>{$data[mysec].score}</td><td><a href='onegame.php?game={$data[mysec].id}'>{$data[mysec].date}</a></td></tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}
