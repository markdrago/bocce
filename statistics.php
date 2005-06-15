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

$page->assign('subtitle',"Office Bocce Scores");

$players = Array();

db_open();

foreach (all_players() as $player) {
	$players[] = Array(
		'id' => $player,
		'name' => player_name($player),
		'wins' => player_total_wins($player),
		'loses' => player_total_losses($player),
		'overall_win_perc' => format_percent(player_overall_win_percentage($player)),
		'overall_deuces_per_game' => format_average(player_overall_deuces_per_game($player)),
		'overall_deuces_against_per_game' => format_average(player_overall_deuces_against_per_game($player)),
		'overall_points_per_game' => format_average(player_overall_points_per_game($player)),
		'overall_points_against_per_game' => format_average(player_overall_points_against_per_game($player)),
		'overall_current_streak' => player_overall_current_streak($player),
		// second table
		'overall_points_per_game_as_loser' => format_average(player_overall_points_per_game_as_loser($player)),
		'overall_points_against_per_game_as_winner' => format_average(player_overall_points_against_per_game_as_winner($player)),
		'overall_bruise_perc' => format_percent(player_overall_bruise_percentage($player)),
		'overall_bruise_attempts_per_game' => format_average(player_overall_bruise_attempts_per_game($player)),
		'overall_rounds_per_game' => format_average(player_overall_rounds_per_game($player)),
		'overall_deuces_per_round' => format_percent(player_overall_deuces_per_round($player)),
		'overall_double_deuce_to_turkey_conv_perc' => format_percent(player_overall_double_deuce_to_turkey_conversion_percentage($player)),
		'overall_coinflip_win_perc' => format_percent(player_overall_coinflip_win_percentage($player)),
		// third table
		'total_deuces' => player_total_deuces($player),
		'total_points_scored' => player_total_points_scored($player),
		'total_points_scored_against' => player_total_points_scored_against($player),
		'total_turkeys' => player_total_turkeys($player),
		'total_tetrises' => player_total_tetrises($player),
		'total_bruises_succ' => player_total_bruises_successful($player),
		'total_bruises_unsecc' => player_total_bruises_unsuccessful($player),
		'total_shutouts' => player_total_shutouts($player),
		'total_been_shutouts' => player_total_been_shutouts($player));
}

$page->assign('players', $players);

$balls = Array();
foreach (all_balls() as $ball) {
	$balls[] = Array(
		'color' => ball_color($ball),
		'type' => ball_type_name(ball_type($ball)),
		'use_perc' => format_percent(ball_overall_use_percentage($ball)),
		'win_perc' => format_percent(ball_overall_win_percentage($ball)),
		'deuces_per_game' => format_percent(ball_overall_deuces_per_game($ball)));
}

db_close();

$page->assign('balls', $balls);
$page->display('statistics.tpl');

?>
