<?php
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

$id = $_GET["id"];
db_open();

$mainname = player_name($id);

?>
<div class="body">
<h1><?=$mainname?>&apos;s Statistics</h1>
<div style="padding-top: 5px; padding-bottom: 15px; font-size: small;">
<a href="statistics.php">Main Statistics</a>
</div>
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
<?

foreach (all_players() as $player) {
  if ($player == $id) {
    continue;
  }

  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($player)."</a></th>\n";
  
  print "<td>".player_total_wins_versus($id, $player)."&nbsp;-&nbsp;";
  print player_total_losses_versus($id, $player)."</td>\n";
  
  print "<td>".
  format_percent(player_overall_win_percentage_versus($id, $player)).
  "</td>\n";
  
  print "<td>".
  format_average(player_overall_deuces_per_game_versus($id, $player)).
  "</td>\n";

  print "<td>".
  format_average(player_overall_deuces_against_per_game_versus($id,
						       $player))."</td>\n";

  print "<td>".
  format_average(player_overall_points_scored_per_game_versus($id,
						       $player))."</td>\n";

  print "<td>".
  format_average(player_overall_points_scored_against_per_game_versus($id,
						       $player))."</td>\n";

  print "<td>".player_overall_current_streak_versus($id, $player)."</td>\n";

  print "</tr>\n";
}

?>
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
<?
foreach (all_players() as $player) {
  if ($player == $id) {
    continue;
  }

  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($player)."</a></th>\n";

  print "<td>".
  format_percent(player_overall_bruise_percentage_versus($id, $player)).
  "</td>\n";

  print "<td>".
  format_average(player_overall_bruises_attempted_per_game_versus($id,
							$player))."</td>\n";

  print "<td>".
  format_percent(player_overall_rounds_per_game_versus($id, $player)).
  "</td>\n";

  print "<td>".
  format_percent(player_overall_deuces_per_round_versus($id, $player)).
  "</td>\n";

  print "<td>".
  format_percent(player_overall_coinflip_win_percentage_versus($id, $player)).
  "</td>\n";

  print "</tr>\n";
}

?>
</table>
<br />

<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Points Scored</th>
<th>Points Scored Against</th>
<th>Bruises</th>
<th>Percent Games Played</th>
<th>Turkeys</th>
<th>Shutouts</th>
<th>Been Shutout</th>
</tr>

<?
foreach (all_players() as $player) {
  if ($player == $id) {
    continue;
  }

  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($player)."</a></th>\n";

  print "<td>".player_total_points_scored_versus($id, $player)."</td>\n";

  print "<td>".player_total_points_scored_against_versus($id,$player).
  "</td>\n";

  print "<td>".player_total_bruises_attempted_versus($id, $player).
  "</td>\n";

  print "<td>".
  format_percent(player_total_games_played_percent_versus($id, $player)).
  "</td>\n";

  print "<td>".player_total_turkeys_versus($id, $player)."</td>\n";

  print "<td>".player_total_shutouts_versus($id, $player)."</td>\n";

  print "<td>".player_total_been_shutouts_versus($id, $player)."</td>\n";

  print "</tr>\n";
}

?>

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
<?
foreach (all_balls() as $ball) {
  print "<tr>";

  print "<th>".ball_color($ball)."</th>\n";
  print "<td>".ball_type_name(ball_type($ball))."</td>\n";

  print "<td>".
  format_percent(player_overall_ball_use_percentage($id, $ball)).
  "</td>\n";

  print "<td>".format_percent(player_overall_win_percentage_with_ball($id,
								      $ball)).
  "</td>\n";

  print "<td>".
  format_percent(player_overall_deuces_per_game_with_ball($id, $ball)).
  "</td>\n";

  print "</tr>\n";
}

db_close();

?>
</table>

</div>
</body>
</html>