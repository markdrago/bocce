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

require("start.php");
require("statslib.php");

//decide which stats we should be displaying
if (isset($_GET['league'])) {
	$type = STAT_LEAGUE;
	$type_value = $_GET['league'];
} elseif (isset($_GET['season'])) {
	$type = STAT_SEASON;
	$type_value = $_GET['season'];
} else {
	$type = STAT_GLOBAL;
	$type_value = 0;
}

$page->assign('subtitle',"Office Bocce Scores");

$players = Array();

db_open();

foreach (all_players($type, $type_value) as $player) {
	$players[] = Array(
		'id' => $player,
		'name' => player_name($player),
		'wins' => player_total_wins($type, $type_value, $player),
		'loses' => player_total_losses($type, $type_value, $player),
		'overall_win_perc' => format_percent(player_overall_win_percentage($type, $type_value, $player)),
		'overall_deuces_per_game' => format_average(player_overall_deuces_per_game($type, $type_value, $player)),
		'overall_deuces_against_per_game' => format_average(player_overall_deuces_against_per_game($type, $type_value, $player)),
		'overall_points_per_game' => format_average(player_overall_points_per_game($type, $type_value, $player)),
		'overall_points_against_per_game' => format_average(player_overall_points_against_per_game($type, $type_value, $player)),
		'overall_current_streak' => player_overall_current_streak($type, $type_value, $player),
		// second table
		'overall_points_per_game_as_loser' => format_average(player_overall_points_per_game_as_loser($type, $type_value, $player)),
		'overall_points_against_per_game_as_winner' => format_average(player_overall_points_against_per_game_as_winner($type, $type_value, $player)),
		'overall_bruise_perc' => format_percent(player_overall_bruise_percentage($type, $type_value, $player)),
		'overall_bruise_attempts_per_game' => format_average(player_overall_bruise_attempts_per_game($type, $type_value, $player)),
		'overall_rounds_per_game' => format_average(player_overall_rounds_per_game($type, $type_value, $player)),
		'overall_deuces_per_round' => format_percent(player_overall_deuces_per_round($type, $type_value, $player)),
		'overall_double_deuce_to_turkey_conv_perc' => format_percent(player_overall_double_deuce_to_turkey_conversion_percentage($type, $type_value, $player)),
		'overall_coinflip_win_perc' => format_percent(player_overall_coinflip_win_percentage($type, $type_value, $player)),
		// third table
		'total_deuces' => player_total_deuces($type, $type_value, $player),
		'total_points_scored' => player_total_points_scored($type, $type_value, $player),
		'total_points_scored_against' => player_total_points_scored_against($type, $type_value, $player),
		'total_turkeys' => player_total_turkeys($type, $type_value, $player),
		'total_tetrises' => player_total_tetrises($type, $type_value, $player),
		'total_bruises_succ' => player_total_bruises_successful($type, $type_value, $player),
		'total_bruises_unsecc' => player_total_bruises_unsuccessful($type, $type_value, $player),
		'total_shutouts' => player_total_shutouts($type, $type_value, $player),
		'total_been_shutouts' => player_total_been_shutouts($type, $type_value, $player));
}

$page->assign('players', $players);

$balls = Array();
foreach (all_balls($type, $type_value) as $ball) {
	$balls[] = Array(
		'color' => ball_color($ball),
		'type' => ball_type_name(ball_type($ball)),
		'use_perc' => format_percent(ball_overall_use_percentage($type, $type_value, $ball)),
		'win_perc' => format_percent(ball_overall_win_percentage($type, $type_value, $ball)),
		'deuces_per_game' => format_percent(ball_overall_deuces_per_game($type, $type_value, $ball)));
}

db_close();

$page->assign('balls', $balls);
$page->display('statistics.tpl');

?>
