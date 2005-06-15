{include file="header.tpl" title=$title}

<br />
<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Record (W-L)</th>
<th>Win Percentage</th>
<th>Deuces Per Game</th>
<th>Deuces Against Per Game</th>
<th>Points per Game</th>
<th>PA per Game</th>
<th>Current Streak</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].wins}&nbsp;-&nbsp;{$players[mysec].loses}</td>
<td>{$players[mysec].overall_win_perc}</td>
<td>{$players[mysec].overall_deuces_per_game}</td>
<td>{$players[mysec].overall_deuces_against_per_game}</td>
<td>{$players[mysec].overall_points_per_game}</td>
<td>{$players[mysec].overall_points_against_per_game}</td>
<td>{$players[mysec].overall_current_streak}</td>
</tr>
{/strip}
{/section}
</table>

<br />

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>PPG as loser</th>
<th>PAPG as winner</th>
<th>Bruise Percentage</th>
<th>Bruise Attempts per Game</th>
<th>Rounds per Game</th>
<th>Deuces per Round</th>
<th>2x Deuce -&gt; Turkey Percentage</th>
<th>Coinflip Win Percentage</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].overall_points_per_game_as_loser}</td>
<td>{$players[mysec].overall_points_against_per_game_as_winner}</td>
<td>{$players[mysec].overall_bruise_perc}</td>
<td>{$players[mysec].overall_bruise_attempts_per_game}</td>
<td>{$players[mysec].overall_rounds_per_game}</td>
<td>{$players[mysec].overall_deuces_per_round}</td>
<td>{$players[mysec].overall_double_deuce_to_turkey_conv_perc}</td>
<td>{$players[mysec].overall_coinflip_win_perc}</td>
</tr>
{/strip}
{/section}
</table>

<br />

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Total Deuces</th>
<th>Points Scored</th>
<th>Points Against</th>
<th>Turkeys</th>
<th>Tetrises</th>
<th>Successful Bruises</th>
<th>Missed Bruises</th>
<th>Shutouts</th>
<th>Been Shutout</th>
</tr>

{section name=mysec loop=$players}
{strip}
<tr>
<th><a href="personal.php?id={$players[mysec].id}">{$players[mysec].name}</a></th>
<td>{$players[mysec].total_deuces}</td>
<td>{$players[mysec].total_points_scored}</td>
<td>{$players[mysec].total_points_scored_against}</td>
<td>{$players[mysec].total_turkeys}</td>
<td>{$players[mysec].total_tetrises}</td>
<td>{$players[mysec].total_bruises_succ}</td>
<td>{$players[mysec].total_bruises_unsecc}</td>
<td>{$players[mysec].total_shutouts}</td>
<td>{$players[mysec].total_been_shutouts}</td>
</tr>
{/strip}
{/section}
</table>

<br />
<h2>Graphs</h2>
<img src="winpercentage.php" alt="Winning Percentage Graph" class="leftimage" />
<img src="deucespergame.php" alt="Deuces per Game Graph" class="rightimage" />
<br/>
<img src="bruisepercentage.php" alt="Bruise Percentage Graph" class="leftimage" />
<img src="rollingwinpercentage.php" alt="Rolling Win Percentage Graph" class="rightimage" />
<br />

<h2>Balls</h2>
<table width="100%" class="stats">
<tr>
<th>Color</th>
<th>Ball Type</th>
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
<td>{$balls[mysec].deuces_per_game}</td>
</tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}
