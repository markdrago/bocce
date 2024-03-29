<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2005 Josef "Jeff" Sipek <jeffpc@josefsipek.net>
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

#get season ID
$id = $_GET["id"];

$page->assign('subtitle',"Office Bocce Scores");

$players = Array();

db_open();

foreach (all_players(STAT_SEASON, $id) as $player) {
	$players[] = Array(
		'id' => $player,
		'name' => player_name($player),
		'wins' => player_total_wins(STAT_SEASON, $id, $player),
		'loses' => player_total_losses(STAT_SEASON, $id, $player),
		'overall_win_perc' => format_percent(player_overall_win_percentage(STAT_SEASON, $id, $player)),
		'overall_deuces_per_game' => format_average(player_overall_deuces_per_game(STAT_SEASON, $id, $player)),
		'overall_deuces_against_per_game' => format_average(player_overall_deuces_against_per_game(STAT_SEASON, $id, $player)),
		'overall_points_per_game' => format_average(player_overall_points_per_game(STAT_SEASON, $id, $player)),
		'overall_points_against_per_game' => format_average(player_overall_points_against_per_game(STAT_SEASON, $id, $player)),
		'overall_current_streak' => player_overall_current_streak(STAT_SEASON, $id, $player),
		// second table
		'overall_points_per_game_as_loser' => format_average(player_overall_points_per_game_as_loser(STAT_SEASON, $id, $player)),
		'overall_points_against_per_game_as_winner' => format_average(player_overall_points_against_per_game_as_winner(STAT_SEASON, $id, $player)),
		'overall_bruise_perc' => format_percent(player_overall_bruise_percentage(STAT_SEASON, $id, $player)),
		'overall_bruise_attempts_per_game' => format_average(player_overall_bruise_attempts_per_game(STAT_SEASON, $id, $player)),
		'overall_rounds_per_game' => format_average(player_overall_rounds_per_game(STAT_SEASON, $id, $player)),
		'overall_deuces_per_round' => format_percent(player_overall_deuces_per_round(STAT_SEASON, $id, $player)),
		'overall_double_deuce_to_turkey_conv_perc' => format_percent(player_overall_double_deuce_to_turkey_conversion_percentage(STAT_SEASON, $id, $player)),
		'overall_coinflip_win_perc' => format_percent(player_overall_coinflip_win_percentage(STAT_SEASON, $id, $player)));
}

$page->assign('players', $players);

db_close();

$page->display('statistics.tpl');

/*
$balls = Array();
foreach (all_balls() as $ball) {
	$balls[] = Array(
		'color' => ball_color($ball),
		'type' => ball_type_name(ball_type($ball)),
		'use_perc' => format_percent(ball_overall_use_percentage(STAT_GLOBAL, 0, $ball)),
		'win_perc' => format_percent(ball_overall_win_percentage(STAT_GLOBAL, 0, $ball)),
		'deuces_per_game' => format_percent(ball_overall_deuces_per_game(STAT_GLOBAL, 0, $ball)));
}

$page->assign('balls', $balls);

		// third table
		'total_deuces' => player_total_deuces(STAT_GLOBAL, 0, $player),
		'total_points_scored' => player_total_points_scored(STAT_GLOBAL, 0, $player),
		'total_points_scored_against' => player_total_points_scored_against(STAT_GLOBAL, 0, $player),
		'total_turkeys' => player_total_turkeys(STAT_GLOBAL, 0, $player),
		'total_tetrises' => player_total_tetrises(STAT_GLOBAL, 0, $player),
		'total_bruises_succ' => player_total_bruises_successful(STAT_GLOBAL, 0, $player),
		'total_bruises_unsecc' => player_total_bruises_unsuccessful(STAT_GLOBAL, 0, $player),
		'total_shutouts' => player_total_shutouts(STAT_GLOBAL, 0, $player),
		'total_been_shutouts' => player_total_been_shutouts(STAT_GLOBAL, 0, $player)
*/
?>
