<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2005 Josef "Jeff" Sipek <jeffpc@optonline.net>
 *
 *This program is free software; you can redistribute it and/or modify
 *it under the terms of the GNU General Public License as published by
 *the Free Software Foundation; either version 2 of the License, or
 *(at your option) any later version.
 *
 *This program is distributed in the hope that it will be useful,
 *but WITHOUT ANY WARRANTY; without even the implied warranty of
 *MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *GNU General Public License for more details.
 *
 *You should have received a copy of the GNU General Public License
 *along with this program; if not, write to the Free Software
 *Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require("db.php");
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

db_open();

foreach (all_players() as $player) {
  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($player)."</a></th>\n";

  print "<td>".player_total_wins($player)."&nbsp;-&nbsp;";
  print player_total_losses($player)."</td>\n";

  print "<td>".format_percent(player_overall_win_percentage($player)).
    "</td>\n";

  print "<td>".format_average(player_overall_deuces_per_game($player)).
    "</td>\n";

  print "<td>".format_average(player_overall_points_per_game($player)).
    "</td>\n";

  print "<td>".format_average(
    player_overall_points_against_per_game($player))."</td>\n";

  print "<td>".format_average(
    player_overall_points_per_game_as_loser($player))."</td>\n";

  print "<td>".format_average(
    player_overall_points_against_per_game_as_winner($player))."</td>\n";

  print "<td>".format_percent(
    player_overall_bruise_percentage($player))."</td>\n";

  print "<td>".format_percent(
    player_overall_bruise_attempts_per_game($player))."</td>\n";

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
<th>Coinflip Win Percentage</th>
</tr>

<?
foreach (all_players() as $player) {
  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($player)."</a></th>\n";

  print "<td>".player_total_deuces($player)."</td>\n";
  print "<td>".player_total_points_scored($player)."</td>\n";
  print "<td>".player_total_points_scored_against($player)."</td>\n";
  print "<td>".player_total_turkeys($player)."</td>\n";
  print "<td>".player_total_tetrises($player)."</td>\n";
  print "<td>".player_total_bruises_successful($player)."</td>\n";
  print "<td>".player_total_bruises_unsuccessful($player)."</td>\n";
  print "<td>".player_total_shutouts($player)."</td>\n";
  print "<td>".player_total_been_shutouts($player)."</td>\n";
  print "<td>".player_overall_current_streak($player)."</td>\n";
  print "<td>".
  format_percent(player_overall_double_deuce_to_turkey_conversion_percentage($player))."</td>";
  print "<td>".
  format_percent(player_overall_coinflip_win_percentage($player))."</td>";
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
foreach (all_balls() as $ball) {
  print "<tr>";

  print "<th>".ball_color($ball)."</th>\n";

  print "<td>".ball_type_name(ball_type($ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_use_percentage($ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_win_percentage($ball))."</td>\n";

  print "<td>".
  format_percent(ball_overall_deuces_per_game($ball))."</td>\n";

  print "</tr>\n";
}

db_close();
?>
</table>


</div>
</body>
</html>