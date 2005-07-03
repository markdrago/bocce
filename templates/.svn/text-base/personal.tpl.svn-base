{include file="header.tpl" title=$title}

<div style="padding-top: 5px; font-size: small;"><a href="statistics.php">Main Statistics</a></div>
{section name=l loop=$leagues}
<div style="font-size: small;"><a href="statistics.php?league={$leagues[l].id}">League: {$leagues[l].name}</a></div>
{/section}
<br />
<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Record</th>
<th>Win Percentage</th>
<th>Deuces Per Game</th>
<th>Deuces Against Per Game</th>
<th>Points Per Game</th>
<th>Points Against Per Game</th>
<th>Current Streak</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].wins}&nbsp;-&nbsp;{$players[mysec].loses}</td>
<td>{$players[mysec].win_perc}</td>
<td>{$players[mysec].deuces}</td>
<td>{$players[mysec].deuces_against}</td>
<td>{$players[mysec].points_per_game}</td>
<td>{$players[mysec].points_against_per_game}</td>
<td>{$players[mysec].streak}</td>
</tr>
{/strip}
{/section}

</table>

<br />

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Bruise Percentage</th>
<th>Bruises Per Game</th>
<th>Rounds per Game</th>
<th>Deuces per Round</th>
<th>Coin Flip Win Percentage</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].bruise_perc}</td>
<td>{$players[mysec].bruise_att_pg}</td>
<td>{$players[mysec].rounds_pg}</td>
<td>{$players[mysec].deuces_pr}</td>
<td>{$players[mysec].coin_wins}</td>
</tr>
{/strip}
{/section}

</table>
<br />

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Points Scored</th>
<th>Points Scored Against</th>
<th>Bruises</th>
<th>Turkeys</th>
<th>Shutouts</th>
<th>Been Shutout</th>
<th>Percent Games Played</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].pts}
<td>{$players[mysec].pts_against}
<td>{$players[mysec].bruise_att}
<td>{$players[mysec].turkeys}
<td>{$players[mysec].shutouts}
<td>{$players[mysec].been_shutouts}
<td>{$players[mysec].games_perc}
</tr>
{/strip}
{/section}

</table>

<br />

<h2>Balls</h2>
<table width="100%" class="stats">
<tr>
<th>Color</th>
<th>Type</th>
<th>Use Percentage</th>
<th>Win Percentage</th>
<th>Deuces Per Game</th>
</tr>

{section name=mysec loop=$balls}
{strip}
<tr>
<th>{$balls[mysec].color}</th>
<td>{$balls[mysec].type}</td>
<td>{$balls[mysec].use_perc}</td>
<td>{$balls[mysec].win_perc}</td>
<td>{$balls[mysec].deuces_pg}</td>
</tr>
{/strip}
{/section}

</table>

{include file="footer.tpl"}
