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

require("start.php");
require("statslib.php");

$id = $_GET["id"];

$type = STAT_GLOBAL;
$type_value = 0;

db_open();

$page->assign('subtitle', player_name($id) . "&apos;s Statistics");

$players = Array();
foreach (all_players($type, $type_value) as $player) {
	if ($player == $id)
		continue;

	$players[] = Array(
		'id' => $player,
		'name' => player_name($player),
 		'wins' => player_total_wins_versus($type, $type_value, $id, $player),
		'loses' => player_total_losses_versus($type, $type_value, $id, $player),
		'win_perc' => format_percent(player_overall_win_percentage_versus($type, $type_value, $id, $player)),
		'deuces' => format_average(player_overall_deuces_per_game_versus($type, $type_value, $id, $player)),
		'deuces_against' => format_average(player_overall_deuces_against_per_game_versus($type, $type_value, $id,$player)),
		'points_per_game' => format_average(player_overall_points_scored_per_game_versus($type, $type_value, $id,$player)),
		'points_against_per_game' => format_average(player_overall_points_scored_against_per_game_versus($type, $type_value, $id, $player)),
		'streak' => player_overall_current_streak_versus($type, $type_value, $id, $player),
		// table 2
		'bruise_perc' => format_percent(player_overall_bruise_percentage_versus($type, $type_value, $id, $player)),
		'bruise_att_pg' => format_average(player_overall_bruises_attempted_per_game_versus($type, $type_value, $id, $player)),
		'rounds_pg' => format_average(player_overall_rounds_per_game_versus($type, $type_value, $id, $player)),
		'deuces_pr' => format_percent(player_overall_deuces_per_round_versus($type, $type_value, $id, $player)),
		'coin_wins' => format_percent(player_overall_coinflip_win_percentage_versus($type, $type_value, $id, $player)),
		// table 3
		'pts' => player_total_points_scored_versus($type, $type_value, $id, $player),
		'pts_against' => player_total_points_scored_against_versus($type, $type_value, $id,$player),
		'bruise_att' => player_total_bruises_attempted_versus($type, $type_value, $id, $player),
		'turkeys' => player_total_turkeys_versus($type, $type_value, $id, $player),
		'shutouts' => player_total_shutouts_versus($type, $type_value, $id, $player),
		'been_shutouts' => player_total_been_shutouts_versus($type, $type_value, $id, $player),
		'games_perc' => format_percent(player_total_games_played_percent_versus($type, $type_value, $id, $player)));
}

$page->assign('players',$players);

$balls = Array();
foreach (all_balls($type, $type_value) as $ball) {
	$balls[] = Array(
		'color' => ball_color($ball),
		'type' => ball_type_name(ball_type($ball)),
		'use_perc' => format_percent(player_overall_ball_use_percentage($type, $type_value, $id, $ball)),
		'win_perc' => format_percent(player_overall_win_percentage_with_ball($type, $type_value, $id, $ball)),
		'deuces_pg' => format_percent(player_overall_deuces_per_game_with_ball($type, $type_value, $id, $ball)));
}

$page->assign('balls', $balls);

$leagues = Array();
$res = db_query("select league.id, league.name FROM league, league_player WHERE league.id = league_player.league AND league_player.player = $id;");
while($row = db_fetch_array($res))
	$leagues[] = Array(
		'id' => $row['id'],
		'name' => $row['name']);
db_close();

$page->assign('leagues', $leagues);

$page->display('personal.tpl');

?>
