{include file="header.tpl" title=$title}

<form action="" method="post">
<input type="hidden" name="stage" value="gameon" />
<center>
<table class="score">
<tr>
<td><h1>{$winner_score}</h1></td>
<td colspan="2"><h1>-</h1></td>
<td><h1>{$loser_score}</h1></td>
</tr>
<tr>
<td><h2>{$winner_name}</h2></td>
<td colspan="2"></td>
<td><h2>{$loser_name}</h2></td>
</tr>
</table>
<br />
<h3>Score History</h3>
<table class="scoreboard">
<tr>
<td>{$winner_name}</td>
{section name=hist loop=$win_hist}
<td>{$win_hist[hist]}</td>
{/section}
</tr>
<tr>
<td>{$loser_name}</td>
{section name=hist loop=$los_hist}
<td>{$los_hist[hist]}</td>
{/section}
</tr>
</table>

<h3>Bruises</h3>
<table class="bruises">
<tr>
<th>Name</th><th>Round</th><th>Success</th>
</tr>
{section name=br loop=$bruises}
<tr>
<td>{$bruises[br].name}</td><td>{$bruises[br].round}</td><td>{$bruises[br].suc}</td>
</tr>
{/section}
</table>
</center>
</form>

{include file="footer.tpl"}
