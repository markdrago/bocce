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

//this file is a repository for all of the calculations & database queries
//needed to create pages that list statistics

//TODO: this function should be documented with phpdocumentor or something
//      like that because there is going to be a _lot_ of functions in here
//      someday.  It'd be nice to get an API doc for free. For now a simple
//      'egrep ^function statslib.php' works pretty nicely


#return an array of all players ids
function all_players($type, $type_value) {
	$players = array();

	$result = db_query("select player.id from player" .
			   __player_from_clause($type). " where " .
			   __player_where_clause($type, $type_value));
	while ($row = db_fetch_array($result)) {
		$players[] = $row["id"];
	}
	return $players;
}

function __player_from_clause($type) {
	switch ($type) {
		case STAT_SEASON:
			return ", season, season_player";
			break;
		case STAT_LEAGUE:
			return ", league, league_player";
			break;
		case STAT_GLOBAL:
		default:
			return "";
			break;
	}
}

function __player_where_clause($type, $type_value) {
	switch ($type) {
		case STAT_SEASON:
			return "(player.id = season_player.player and " .
			       "season_player.season = season.id and " .
			       "season.id = $type_value)";
			break;
		case STAT_LEAGUE:
			return "(player.id = league_player.player and " .
			       "league_player.league = league.id and " .
			       "league.id = $type_value)";
			break;
		case STAT_GLOBAL:
		default:
			return "'1'";
			break;
	}
}

function __game_from_clause($type) {
	switch($type) {
		case STAT_SEASON:
			return ", season";
			break;
		case STAT_LEAGUE:
			return ", season, league";
			break;
		case STAT_GLOBAL:
		default:
			return "";
			break;
	}
}

function __game_where_clause($type, $type_value) {
	switch ($type) {
		case STAT_SEASON:
			return "(game.season = season.id and " .
			       "season.id = $type_value)";
			break;
		case STAT_LEAGUE:
			return "(game.season = season.id and " .
			       "season.league = league.id and " .
			       "league.id = $type_value)";
			break;
		case STAT_GLOBAL:
		default:
			return "'1'";
	}
}

function __game_link_from_clause($type) {
	switch ($type) {
		case STAT_SEASON:
			return ", game, season";
			break;
		case STAT_LEAGUE:
			return ", game, season, league";
			break;
		case STAT_GLOBAL:
		default:
			return "";
			break;
	}
}

function __game_link_where_clause($tablename, $type, $type_value) {
	switch ($type) {
		case STAT_SEASON:
			return "($tablename.game = game.id and " .
			       "game.season = season.id and " .
			       "season.id = $type_value)";
			break;
		case STAT_LEAGUE:
			return "($tablename.game = game.id and " .
			       "game.season = season.id and " .
			       "season.league = league.id and " .
			       "league.id = $type_value)";
			break;
		case STAT_GLOBAL:
		default:
			return "'1'";
			break;
	}
}

#get player's total number of wins
function player_total_wins($type, $type_value, $id) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner = $id and " .
		     	   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of losses
function player_total_losses($type, $type_value, $id) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "game.loser = $id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of games played
function player_total_games_played($type, $type_value, $id) {
	return clean_value(player_total_wins($type, $type_value, $id) +
		     player_total_losses($type, $type_value, $id));
}

#get player's total points scored
function player_total_points_scored($type, $type_value, $id) {
	return clean_value(player_total_points_scored_as_winner($type, $type_value, $id) +
		     player_total_points_scored_as_loser($type, $type_value, $id));
}

#get player's total points scored against
function player_total_points_scored_against($type, $type_value, $id) {
	return clean_value(player_total_points_scored_against_as_winner($type, $type_value, $id) +
		     player_total_points_scored_against_as_loser($type, $type_value, $id));
}

#get player's total number of points scored as a loser
function player_total_points_scored_as_loser($type, $type_value, $id) {
	$result = db_query("select sum(game.loser_points) from game" .
			   __game_from_clause($type) . " where ".
			   "game.loser=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of points scored as a winner
function player_total_points_scored_as_winner($type, $type_value, $id) {
	$result = db_query("select sum(game.winner_points) from game" .
			   __game_from_clause($type) . " where ".
			   "game.winner=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of points scored against as winner
function player_total_points_scored_against_as_winner($type, $type_value, $id) {
	$result = db_query("select sum(game.loser_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of points scored against as loser
function player_total_points_scored_against_as_loser($type, $type_value, $id) {
	$result = db_query("select sum(game.winner_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.loser=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of deuces
function player_total_deuces($type, $type_value, $id) {
	$result = db_query("select count(*) from point" .
			   __game_link_from_clause($type) . " where ".
			   "point.scorer=$id and point.amount=2 and " .
			   __game_link_where_clause("point", $type,
						    $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_deuces_against($type, $type_value, $id) {
	$result = db_query("select count(*) from game, point" .
			   __game_from_clause($type) . " where ".
			   "((game.winner!=$id and game.loser=$id and ".
			   "game.winner=point.scorer) or (game.loser!=$id and ".
			   "game.winner=$id and game.loser=point.scorer)) and ".
			   "point.amount=2 and point.game=game.id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's number of points scored against as a winner
function player_points_scored_against_as_winner($type, $type_value, $id) {
	$result = db_query("select sum(game.loser_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's number of points scored against as a loser
function player_points_scored_against_as_loser($type, $type_value, $id) {
	$result = db_query("select sum(game.winner_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.loser=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of good bruises
function player_total_bruises_successful($type, $type_value, $id) {
	$result = db_query("select count(*) from bruise" .
			   __game_link_from_clause($type) . " where ".
			   "bruise.player=$id and bruise.success=1 and " .
			   __game_link_where_clause("bruise", $type,
						    $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of missed bruises
function player_total_bruises_unsuccessful($type, $type_value, $id) {
	$result = db_query("select count(*) from bruise" . 
			   __game_link_from_clause($type) . " where " .
			   "bruise.player=$id and bruise.success=0 and " .
			   __game_link_where_clause("bruise", $type,
						    $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of attempted bruises
function player_total_bruises_attempted($type, $type_value, $id) {
	$result = db_query("select count(*) from bruise" . 
			   __game_link_from_clause($type) . " where ".
			   "bruise.player=$id and " .
			   __game_link_where_clause("bruise", $type,
						    $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's number of shutouts
function player_total_shutouts($type, $type_value, $id) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where ".
			   "game.winner=$id and game.loser_points=0 and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get number of times a player has been shutout
function player_total_been_shutouts($type, $type_value, $id) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where ".
			   "game.loser=$id and game.loser_points=0 and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of tetrises
function player_total_tetrises($type, $type_value, $id) {
	$result = db_query("select count(*) from point as point1, point as point2, point as point3, point as point4" .
			   __game_link_from_clause($type) . " where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point4.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point4.id=point3.id+1 and point4.amount=2 and point1.game=point2.game and point2.game=point3.game and point3.game=point4.game and " . __game_link_where_clause("point1", $type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of turkeys
function player_total_turkeys($type, $type_value, $id) {
	$result = db_query("select count(*) from point as point1, point as point2, point as point3" .
			   __game_link_from_clause($type) . " where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point1.game=point2.game and point2.game=point3.game and " __game_link_where_clause("point1", $type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0] - (player_total_tetrises($type, $type_value, $id) * 2));
}

#get player's total number of times in turkey territory
function player_total_double_deuces_with_possible_turkey($type, $type_value, $id) {
	/* in this query, point0 is a placeholder for the point before the double
	 * deuce.  This helps us avoid scoring a turkey as two double deuces
	 * (2,[2),2]  <--- first double is in paranthesis and second is in brackets
	 * FIXME: this query doesn't work on the first point in the DB
	 */
	$result = db_query("select count(*) from point as point0, point as point1, point as point2, point as point3" .
			   __game_link_from_clause($type) . " where ((point0.id+1=point1.id) and (point0.amount!=2 or point0.scorer!=$id or point0.game!=point1.game)) and point1.scorer=$id and point2.scorer=$id and point1.amount=2 and point2.amount=2 and point2.id=point1.id+1 and point3.id=point2.id+1 and point1.game=point2.game and point2.game=point3.game and " . __game_link_where_clause("point1", $type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's overall current streak
function player_overall_current_streak($type, $type_value, $id) {
	/* get list of every game this player played in, sorted backwards by date */
	$result = db_query("select winner, loser from game" .
			   __game_from_clause($type) . " where " .
			   "winner=$id or loser=$id and " .
			   __game_where_clause($type, $type_value) .
			   " order by dts desc");
  
	$count = 0;

	#figure out what happened in the last game
	$row1 = db_fetch_array($result);
	if ($row1['winner'] == $id) {
		$tag = "W";
		$key = "winner";
		$count = 1;
	} elseif ($row1['loser'] == $id) {
		$tag = "L";
		$key = "loser";
		$count = 1;
	}
	
	#count how many times whatever happend last game happened before that
	#without anything else happening
	while($row = db_fetch_array($result)) {
		if ($row[$key] == $id)
			$count++;
		else
			break;
	}
	
	#clean up count and return the number with a tag (W or L)
	if ($count > 0) {
		$count = clean_value($count);
		return "$count $tag";
	}
	
	return "None";
}

function player_total_coinflips_won($type, $type_value, $id) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where ".
			   "game.coinflip_winner=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);  
}

function player_total_rounds_played($type, $type_value, $id) {
	$result = db_query("select count(*) from point, game" .
			   __game_from_clause($type) . " where ".
			   "(game.winner=$id or game.loser=$id) and ".
			   "point.game=game.id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

/******************************
 * Stats that reference balls *
 ******************************/

#return number of times this player has used this ball
function player_total_games_played_with_ball($type, $type_value, $id, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";
	$loserfield  = "loser_ball$type";

	$result = db_query("select count(*) from game" .
			   __game_from_clause($type, $type_value) . " where " .
			   "(winner=$id and $winnerfield=$ball) or " .
			   "(loser=$id and $loserfield=$ball) and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	
	return clean_value($row[0]);
}

#return number of times this player has won with this ball
function player_total_games_won_with_ball($type, $type_value, $id, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";

	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "winner=$id and $winnerfield=$ball and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);

	return clean_value($row[0]);
}

function player_total_deuces_with_ball($type, $type_value, $id, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";
	$loserfield  = "loser_ball$type";

	$result = db_query("select count(*) from point, game" .
			   __game_from_clause($type) . " where " .
			   "point.game = game.id and point.amount=2 and " .
			   "((game.winner=$id and point.scorer=game.winner and".
			   " game.$winnerfield=$ball) or (game.loser=$id and " .
			   "point.scorer=game.loser and " .
			   "game.$loserfield=$ball)) and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	
	return clean_value($row[0]);
}

function player_overall_deuces_per_game_with_ball($type, $type_value, $id, $ball) {
	$games_played_with_ball = player_total_games_played_with_ball($type, $type_value, $id,$ball);
	if ($games_played_with_ball == 0) {
		return 0;
	}

	$deuces_scored_with_ball = player_total_deuces_with_ball($type, $type_value, $id, $ball);
	return clean_value($deuces_scored_with_ball / $games_played_with_ball);
}

function player_overall_win_percentage_with_ball($type, $type_value, $id, $ball) {
	$games_played_with_ball = player_total_games_played_with_ball($type, $type_value, $id,$ball);
	if ($games_played_with_ball == 0)
		return 0;

	$games_won_with_ball = player_total_games_won_with_ball($type, $type_value, $id, $ball);
	
	return clean_value($games_won_with_ball / $games_played_with_ball);
}

function player_overall_ball_use_percentage($type, $type_value, $id, $ball) {
	$games_played = player_total_games_played($type, $type_value, $id);
	if ($games_played == 0)
		return 0;
	$games_played_with_ball = player_total_games_played_with_ball($type, $type_value, $id,$ball);
	
	return clean_value($games_played_with_ball / $games_played);
}

/***************************************
 * Stats that reference another player *
 ***************************************/

function player_total_wins_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner = $id and game.loser = $player and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_losses_versus($type, $type_value, $id, $player) {
	//this is just the reverse of player_total_wins_versus
	return player_total_wins_versus($type, $type_value, $player, $id);
}

function player_total_points_scored_as_winner_versus($type, $type_value, $id, $player) {
	$result = db_query("select sum(game.winner_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner = $id and game.loser = $player and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_points_scored_as_loser_versus($type, $type_value, $id, $player) {
	$result = db_query("select sum(game.loser_points) from game" .
			   __game_from_clause($type) . " where " .
			   "game.winner = $player and game.loser = $id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_points_scored_against_as_loser_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_total_points_scored_as_winner_versus
	return player_total_points_scored_as_winner_versus($type, $type_value, $player, $id);
}

function player_total_points_scored_against_as_winner_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_total_points_scored_as_loser_versus
	return player_total_points_scored_as_loser_versus($type, $type_value, $player, $id);
}

function player_total_points_scored_versus($type, $type_value, $id, $player) {
	return player_total_points_scored_as_loser_versus($type, $type_value, $id, $player) +
		player_total_points_scored_as_winner_versus($type, $type_value, $id, $player);
}

function player_total_points_scored_against_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_total_points_scored_versus
	return player_total_points_scored_versus($type, $type_value, $player, $id);
}  

function player_total_deuces_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game, point" .
			   __game_from_clause($type) . " where " .
			   "game.id = point.game and point.scorer = $id and " .
			   "point.amount = 2 and ((game.winner = $player and " .
			   "game.loser = $id) or (game.loser = $player and " .
			   "game.winner = $id)) and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_deuces_against_versus($type, $type_value, $id, $player) {
	return player_total_deuces_versus($type, $type_value, $player, $id);
}

function player_total_shutouts_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) .
			   " where game.winner = $id and " .
			   "game.loser = $player and game.loser_points=0 and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_been_shutouts_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_total_shutouts_versus
	return player_total_shutouts_versus($type, $type_value, $player, $id);
}

function player_total_bruises_successful_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from bruise, game" .
			   __game_from_clause($type) . " where " .
			   "((game.winner = $id and game.loser = $player) or " .
			   "(game.loser = $id and game.winner = $player)) and ".
			   "game.id = bruise.game and bruise.player = $id and ".
			   "bruise.success = 1 and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_bruises_missed_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from bruise, game" .
			   __game_from_clause($type) . " where " .
			   "((game.winner = $id and game.loser = $player) or ".
			   "(game.loser = $id and game.winner = $player)) and ".
			   "game.id = bruise.game and bruise.player = $id and ".
			   "bruise.success != 1 and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}  

function player_total_bruises_attempted_versus($type,$type_value,$id, $player) {
	$result = db_query("select count(*) from bruise, game" .
			   __game_from_clause($type) . " where " .
			   "((game.winner = $id and game.loser = $player) or ".
			   "(game.loser = $id and game.winner = $player)) and ".
			   "game.id = bruise.game and bruise.player = $id and ".
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_games_played_versus($type, $type_value, $id, $player) {
	return player_total_wins_versus($type, $type_value, $id, $player) +
		player_total_losses_versus($type, $type_value, $id, $player);
}

#get player's total number of tetrises
function player_total_tetrises_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game, point as point1, point as point2, point as point3, point as point4" . __game_from_clause($type) . " where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point4.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point4.id=point3.id+1 and point4.amount=2 and point1.game=point2.game and point2.game=point3.game and point3.game=point4.game and point1.game = game.id and ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player)) and " . __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's total number of turkeys
function player_total_turkeys_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game, point as point1, point as point2, point as point3" . __game_from_clause($type) . " where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point1.game=point2.game and point2.game=point3.game and point1.game = game.id and ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player)) and " . __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0] - (player_total_tetrises($type, $type_value, $id) * 2));
}

function player_total_coinflips_won_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "(game.winner=$player or game.loser=$player) and ".
			   "game.coinflip_winner=$id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

function player_total_rounds_played_versus($type, $type_value, $id, $player) {
	$result = db_query("select count(*) from point, game" .
			   __game_from_clause($type) . " where " .
			   "((game.winner=$player and game.loser=$id) ".
			   "or (game.winner=$id and game.loser=$player)) ".
			   "and point.game=game.id and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#get player's overall current streak
function player_overall_current_streak_versus($type, $type_value, $id, $player) {
	$result = db_query("select winner, loser from game" .
			   __game_from_clause($type) . " where " .
			   "(winner=$id and loser=$player) or " .
			   "(loser=$id and winner=$player) and " .
			   __game_where_clause($type, $type_value) .
			   "order by dts desc");
  
	$count = 0;

	#figure out what happened in the last game
	$row1 = db_fetch_array($result);
	if ($row1['winner'] == $id) {
		$tag = "W";
		$key = "winner";
		$count = 1;
	} elseif ($row1['loser'] == $id) {
		$tag = "L";
		$key = "loser";
		$count = 1;
	}

	/* count how many times whatever happend last game happened before that
	 * without anything else happening
	 */
	while($row = db_fetch_array($result)) {
		if ($row[$key] == $id)
			$count++;
		else
			break;
	}

	#clean up count and return the number with a tag (W or L)
	if ($count > 0) {
		$count = clean_value($count);
		return "$count $tag";
	}
	
	return "None";
}

/******************************************
 * Overall Stats that require calculation *
 ******************************************/

function player_overall_win_percentage($type, $type_value, $id) {
	$games_played = player_total_games_played($type, $type_value, $id);

	if ($games_played == 0) {
		return 0;
	}

	$total_wins = player_total_wins($type, $type_value, $id);
	return clean_value($total_wins / $games_played);
}

function player_overall_points_per_game($type, $type_value, $id) {
	$games_played = player_total_games_played($type, $type_value, $id);

	if ($games_played == 0) {
		return 0;
	}

	$total_points = player_total_points_scored($type, $type_value, $id);
	return clean_value($total_points / $games_played);
}

function player_overall_points_against_per_game($type, $type_value, $id) {
	$games_played = player_total_games_played($type, $type_value, $id);

	if ($games_played == 0) {
		return 0;
	}

	$total_points_against = player_total_points_scored_against($type, $type_value, $id);
	return clean_value($total_points_against / $games_played);
}

function player_overall_deuces_per_game($type, $type_value, $id) {
	$games_played = player_total_games_played($type, $type_value, $id);

	if ($games_played == 0) {
		return 0;
	}

	$total_deuces = player_total_deuces($type, $type_value, $id);
	return clean_value($total_deuces / $games_played);
}

function player_overall_deuces_against_per_game($type, $type_value, $id) {
	$games_played = player_total_games_played($type, $type_value, $id);

	if ($games_played == 0) {
		return 0;
	}

	$total_deuces_against = player_total_deuces_against($type, $type_value, $id);
	return clean_value($total_deuces_against / $games_played);
}

function player_overall_points_per_game_as_loser($type, $type_value, $id) {
	$total_losses = player_total_losses($type, $type_value, $id);

	if ($total_losses == 0) {
		return 0;
	}

	$total_points_as_loser = player_total_points_scored_as_loser($type, $type_value, $id);
	return clean_value($total_points_as_loser / $total_losses);
}

function player_overall_points_against_per_game_as_winner($type, $type_value, $id) {
	$total_wins = player_total_wins($type, $type_value, $id);

	if ($total_wins == 0) {
		return 0;
	}

	$total_points_against_as_winner =
		player_total_points_scored_against_as_winner($type, $type_value, $id);

	return clean_value($total_points_against_as_winner / $total_wins);
}

function player_overall_bruise_percentage($type, $type_value, $id) {
	$total_bruise_attempts = player_total_bruises_attempted($type, $type_value, $id);

	if ($total_bruise_attempts == 0) {
		return 0;
	}

	$good_bruises = player_total_bruises_successful($type, $type_value, $id);
	return clean_value($good_bruises / $total_bruise_attempts);
}

function player_overall_bruise_attempts_per_game($type, $type_value, $id) {
	$total_games_played = player_total_games_played($type, $type_value, $id);

	if ($total_games_played == 0) {
		return 0;
	}

	$total_bruise_attempts = player_total_bruises_attempted($type, $type_value, $id);
	return clean_value($total_bruise_attempts / $total_games_played);
}

function player_overall_double_deuce_to_turkey_conversion_percentage($type, $type_value, $id) {
	$total_turkey_territories =
		player_total_double_deuces_with_possible_turkey($type, $type_value, $id);

	if ($total_turkey_territories == 0) {
		return 0;
	}

	$total_turkeys = player_total_turkeys($type, $type_value, $id);
	return clean_value($total_turkeys / $total_turkey_territories);
}

function player_overall_coinflip_win_percentage($type, $type_value, $id) {
	$total_games_played = player_total_games_played($type, $type_value, $id);
  
	if ($total_games_played == 0) {
		return 0;
	}

	$total_coinflips_won = player_total_coinflips_won($type, $type_value, $id);
	return clean_value($total_coinflips_won / $total_games_played);
}

function player_overall_rounds_per_game($type, $type_value, $id) {
	$total_games_played = player_total_games_played($type, $type_value, $id);
  
	if ($total_games_played == 0) {
		return 0;
	}

	$total_rounds_played = player_total_rounds_played($type, $type_value, $id);
	return clean_value($total_rounds_played / $total_games_played);
}

function player_overall_deuces_per_round($type, $type_value, $id) {
	$total_rounds_played = player_total_rounds_played($type, $type_value, $id);

	if ($total_rounds_played == 0) {
		return 0;
	}

	$total_deuces = player_total_deuces($type, $type_value, $id);
	return clean_value($total_deuces / $total_rounds_played);
}

/***************************************************************
 * Stats that reference another player and require calculation *
 ***************************************************************/

function player_overall_win_percentage_versus($type, $type_value, $id, $player) {
	$total_games_played_versus =
		player_total_games_played_versus($type, $type_value, $id, $player);
  
	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_wins_versus = player_total_wins_versus($type, $type_value, $id, $player);
	return clean_value($total_wins_versus / $total_games_played_versus);
}

function player_overall_deuces_per_game_versus($type, $type_value, $id, $player) {
	$total_games_played_versus = 
		player_total_games_played_versus($type, $type_value, $id, $player);
  
	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_deuces_versus = player_total_deuces_versus($type, $type_value, $id, $player);
	return clean_value($total_deuces_versus / $total_games_played_versus);
}

function player_overall_deuces_against_per_game_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_overall_deuces_per_game_versus
	return player_overall_deuces_per_game_versus($type, $type_value, $player, $id);
}

function player_overall_points_scored_per_game_versus($type, $type_value, $id, $player) {
	$total_games_played_versus = 
		player_total_games_played_versus($type, $type_value, $id, $player);
  
	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_points_scored_versus =
		player_total_points_scored_versus($type, $type_value, $id, $player);
	return clean_value($total_points_scored_versus / $total_games_played_versus);
}
  
function player_overall_points_scored_against_per_game_versus($type, $type_value, $id, $player) {
	//this is the reverse of player_overall_points_scored_per_game_versus
	return player_overall_points_scored_per_game_versus($type, $type_value, $player, $id);
}

function player_overall_bruise_percentage_versus($type, $type_value, $id, $player) {
	$total_bruises_attempted_versus =
		player_total_bruises_attempted_versus($type, $type_value, $id, $player);

	if ($total_bruises_attempted_versus == 0) {
		return 0;
	}

	$total_bruises_successful_versus =
		player_total_bruises_successful_versus($type, $type_value, $id, $player);

	return clean_value($total_bruises_successful_versus /
		$total_bruises_attempted_versus);
}

function player_overall_bruises_attempted_per_game_versus($type, $type_value, $id, $player) {
	$total_games_played_versus = 
		player_total_games_played_versus($type, $type_value, $id, $player);
  
	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_bruises_attempted_versus =
		player_total_bruises_attempted_versus($type, $type_value, $id, $player);
	return clean_value($total_bruises_attempted_versus /
		$total_games_played_versus);
}

function player_total_games_played_percent_versus($type, $type_value, $id, $player) {
	$total_games_played_versus =
		player_total_games_played_versus($type, $type_value, $id, $player);

	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_games_played = player_total_games_played($type, $type_value, $id);
	return clean_value($total_games_played_versus / $total_games_played);
}

function player_overall_coinflip_win_percentage_versus($type, $type_value, $id, $player) {
	$total_games_played_versus =
		player_total_games_played_versus($type, $type_value, $id, $player);

	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_coinflips_won_against=player_total_coinflips_won_versus($type, $type_value, $id, $player);

	return clean_value($total_coinflips_won_against/$total_games_played_versus);
}


function player_overall_rounds_per_game_versus($type, $type_value, $id, $player) {
	$total_games_played_versus =
		player_total_games_played_versus($type, $type_value, $id, $player);

	if ($total_games_played_versus == 0) {
		return 0;
	}

	$total_rounds_played_versus = player_total_rounds_played_versus($type, $type_value, $id,$player);
	return clean_value($total_rounds_played_versus / $total_games_played_versus);
}

function player_overall_deuces_per_round_versus($type, $type_value, $id, $player) {
	$total_rounds_played_versus = player_total_rounds_played_versus($type, $type_value, $id,$player);
  
	if ($total_rounds_played_versus == 0) {
		return 0;
	}

	$total_deuces_versus = player_total_deuces_versus($type, $type_value, $id, $player);
	return clean_value($total_deuces_versus / $total_rounds_played_versus);
}

/***************************************
 * functions that deal with ball color *
 ***************************************/

#get an array of all of the ball IDs
function all_balls($type, $type_value) {
	$balls = array();

	$result = db_query("select id from ball");
	while ($row = db_fetch_array($result)) {
		$balls[] = $row["id"];
	}

	return $balls;
}

#get an array of all of the bruiser's ball IDs
function all_bruisers($type, $type_value) {
	$bruiser_num = 1;

	$balls = array();
	$result = db_query("select id from ball where num=$bruiser_num");
	while ($row = db_fetch_array($result)) {
		$balls[] = $row["id"];
	}

	return $balls;
}

#get an array of all of the small ball's ball IDs
function all_small_balls($type, $type_value) {
	$small_num = 2;

	$balls = array();
	$result = db_query("select id from ball where num=$small_num");
	while ($row = db_fetch_array($result)) {
		$balls[] = $row["id"];
	}

	return $balls;
}

#get the type of the ball as an integer
function ball_type($ball) {
	$result = db_query("select num from ball where id=$ball");
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}
  
#return the total number of games that have been played with this ball
function ball_total_games_played($type, $type_value, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";
	$loserfield  = "loser_ball$type";

	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "($winnerfield=$ball or $loserfield=$ball) and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#return the total number of games that have been won with this ball
function ball_total_games_won($type, $type_value, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";

	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   "$winnerfield=$ball and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}  

#number of deuces scored with this ball color
function ball_total_deuces_scored($type, $type_value, $ball) {
	$type = ball_type($ball);
	$winnerfield = "winner_ball$type";
	$loserfield  = "loser_ball$type";

	$result = db_query("select count(*) from point, game" .
			   __game_from_clause($type) . " where " .
			   "point.game = game.id and point.amount=2 and " .
			   "((point.scorer=game.winner and " . 
			   "game.$winnerfield=$ball) or " .
			   "(point.scorer=game.loser and " .
			   "game.$loserfield=$ball)) and " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

#return the deuces per game when played with a certain ball
function ball_overall_deuces_per_game($type, $type_value, $ball) {
	$ball_total_games_played = ball_total_games_played($type, $type_value, $ball);
  
	if ($ball_total_games_played == 0) {
		return 0;
	}

	$ball_total_deuces = ball_total_deuces_scored($type, $type_value, $ball);
	return clean_value($ball_total_deuces / $ball_total_games_played);
}

#return the frequency which this ball is played with overall
function ball_overall_use_percentage($type, $type_value, $ball) {
	$universe_num_games = universe_total_games_played($type, $type_value);

	if ($universe_num_games == 0) {
		return 0;
	}

	$ball_total_games_played = ball_total_games_played($type, $type_value, $ball);
	return clean_value($ball_total_games_played / $universe_num_games);
}

#return the win percentage of this ball
function ball_overall_win_percentage($type, $type_value, $ball) {
	$ball_total_games_played = ball_total_games_played($type, $type_value, $ball);

	if ($ball_total_games_played == 0) {
		return 0;
	}

	$ball_games_won = ball_total_games_won($type, $type_value, $ball);

	return clean_value($ball_games_won / $ball_total_games_played);
}

/*************************************************
 * functions that deal with universe-wide things *
 *************************************************/

#return the total number of games ever played
function universe_total_games_played($type, $type_value) {
	$result = db_query("select count(*) from game" .
			   __game_from_clause($type) . " where " .
			   __game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
	return clean_value($row[0]);
}

/************************************
 * functions that aid in formatting *
 ************************************/

function format_percent($value) {
	return sprintf("%0.3f", $value);
}

function format_average($value) {
	return sprintf("%01.2f", $value);
}

?>
