<?
require("boccelib.php");
require("statslib.php");
require("header.php");
require("side.php");
?>
<div class="body">
<h1>Office Bocce Scores</h1>
<br />
<h2>Player Stats</h2>

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Record (W-L)</th>
<th>Win Percentage</th>
<th>Deuces Per Game</th>
<th>Points per Game</th>
<th>PA per Game</th>
<th>PPG as loser</th>
<th>PAPG as winner</th>
<th>Bruise Percentage</th>
<th>Bruise Attempts per Game</th>
</tr>
<?

$db = sqlite_open($database_file);

foreach (all_players($db) as $player) {
  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($db, $player)."</a></th>\n";

  print "<td>".player_total_wins($db, $player)."&nbsp;-&nbsp;";
  print player_total_losses($db, $player)."</td>\n";

  print "<td>".format_percent(player_overall_win_percentage($db, $player)).
    "</td>\n";

  print "<td>".format_average(player_overall_deuces_per_game($db, $player)).
    "</td>\n";

  print "<td>".format_average(player_overall_points_per_game($db, $player)).
    "</td>\n";

  print "<td>".format_average(
    player_overall_points_against_per_game($db, $player))."</td>\n";

  print "<td>".format_average(
    player_overall_points_per_game_as_loser($db, $player))."</td>\n";

  print "<td>".format_average(
    player_overall_points_against_per_game_as_winner($db, $player))."</td>\n";

  print "<td>".format_percent(
    player_overall_bruise_percentage($db, $player))."</td>\n";

  print "<td>".format_percent(
    player_overall_bruise_attempts_per_game($db, $player))."</td>\n";

  print "</tr>\n";
}

?>
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
<th>Current Streak</th>
<th>2x Deuce -> Turkey Percentage</th>
</tr>

<?
foreach (all_players($db) as $player) {
  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($db, $player)."</a></th>\n";

  print "<td>".player_total_deuces($db, $player)."</td>\n";
  print "<td>".player_total_points_scored($db, $player)."</td>\n";
  print "<td>".player_total_points_scored_against($db, $player)."</td>\n";
  print "<td>".player_total_turkeys($db, $player)."</td>\n";
  print "<td>".player_total_tetrises($db, $player)."</td>\n";
  print "<td>".player_total_bruises_successful($db, $player)."</td>\n";
  print "<td>".player_total_bruises_unsuccessful($db, $player)."</td>\n";
  print "<td>".player_total_shutouts($db, $player)."</td>\n";
  print "<td>".player_total_been_shutouts($db, $player)."</td>\n";
  print "<td>".player_overall_current_streak($db, $player)."</td>\n";
  print "<td>".
  format_percent(player_overall_double_deuce_to_turkey_conversion_percentage($db, $player))."</td>";
  print "</tr>\n";
}

?>
</table>

<br />
<h2>Graphs</h2>
<table id="graphs">
<tr>
<td><img src="winpercentage.php" alt="Winning Percentage Graph" /></td>
<td><img src="deucespergame.php" alt="Deuces per Game Graph" /></td>
</tr>
<tr>
<td><img src="bruisepercentage.php" alt="Bruise Percentage Graph" /></td>
<td><img src="rollingwinpercentage.php" alt="Rolling Win Percentage Graph" /></td>
</tr>
</table>
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

<?
foreach (all_balls($db) as $ball) {
  print "<tr>";

  print "<th>".ball_color($db, $ball)."</th>\n";

  print "<td>".ball_type_name(ball_type($db, $ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_use_percentage($db, $ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_win_percentage($db, $ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_deuces_per_game($db, $ball))."</td>\n";

  print "</tr>\n";
}

sqlite_close($db);
?>
</table>


</div>
</body>
</html>