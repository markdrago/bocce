<?php
/*
 * Copyright (C) 2004, 2005 Mark Drago
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

require("boccelib.php");
require("statslib.php");
require("header.php");
require("side.php");

$id = $_GET["id"];
$db = sqlite_open($database_file);

$mainname = player_name($db, $id);

?>
<div class="body">
<h1><?=$mainname?>&apos;s Statistics</h1>
<br />
<table width="100%" class="stats">
<tr>
<th>Name</th>
<th>Record Against</th>
<th>Win Percentage Against</th>
<th>Deuces Per Game</th>
<th>Deuces Against Per Game</th>
<th>Points Per Game</th>
<th>Points Against Per Game</th>
<th>Bruise Percentage</th>
</tr>
<?

foreach (all_players($db) as $player) {
  if ($player == $id) {
    continue;
  }

  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($db, $player)."</a></th>\n";
  
  print "<td>".player_total_wins_versus($db, $id, $player)."&nbsp;-&nbsp;";
  print player_total_losses_versus($db, $id, $player)."</td>\n";
  
  print "<td>".
  format_percent(player_overall_win_percentage_versus($db, $id, $player)).
  "</td>\n";
  
  print "<td>".
  format_average(player_overall_deuces_per_game_versus($db, $id, $player)).
  "</td>\n";

  print "<td>".
  format_average(player_overall_deuces_against_per_game_versus($db, $id,
						       $player))."</td>\n";

  print "<td>".
  format_average(player_overall_points_scored_per_game_versus($db, $id,
						       $player))."</td>\n";

  print "<td>".
  format_average(player_overall_points_scored_against_per_game_versus($db, $id,
						       $player))."</td>\n";

  print "<td>".
  format_percent(player_overall_bruise_percentage_versus($db, $id, $player)).
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
<th>Bruises Per Game</th>
<th>Bruises</th>
<th>Percent Games Played Against</th>
<th>Turkeys</th>
<th>Shutouts</th>
<th>Been Shutout</th>
</tr>
<?
foreach (all_players($db) as $player) {
  if ($player == $id) {
    continue;
  }

  print "<tr>";

  print "<th><a href=\"personal.php?id=$player\">";
  print player_name($db, $player)."</a></th>\n";

  print "<td>".player_total_points_scored_versus($db, $id, $player)."</td>\n";

  print "<td>".player_total_points_scored_against_versus($db,$id,$player).
  "</td>\n";

  print "<td>".
  format_average(player_overall_bruises_attempted_per_game_versus($db, $id,
							$player))."</td>\n";

  print "<td>".player_total_bruises_attempted_versus($db, $id, $player).
  "</td>\n";

  print "<td>".
  format_percent(player_total_games_played_percent_versus($db, $id, $player)).
  "</td>\n";

  print "<td>".player_total_turkeys_versus($db, $id, $player)."</td>\n";

  print "<td>".player_total_shutouts_versus($db, $id, $player)."</td>\n";

  print "<td>".player_total_been_shutouts_versus($db, $id, $player)."</td>\n";

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
foreach (all_balls($db) as $ball) {
  print "<tr>";

  print "<th>".ball_color($db, $ball)."</th>\n";
  print "<td>".ball_type_name(ball_type($db, $ball))."</td>\n";

  print "<td>".
  format_percent(player_overall_ball_use_percentage($db, $id, $ball)).
  "</td>\n";

  print "<td>".format_percent(player_overall_win_percentage_with_ball($db,$id,
								      $ball)).
  "</td>\n";

  print "<td>".
  format_percent(player_overall_deuces_per_game_with_ball($db, $id, $ball)).
  "</td>\n";

  print "</tr>\n";
}

sqlite_close($db);

?>
</table>

</div>
</body>
</html>