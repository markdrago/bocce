<?
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

//this file is a repository for all of the calculations & database queries
//needed to create pages that list statistics

//TODO: this function should be documented with phpdocumentor or something
//      like that because there is going to be a _lot_ of functions in here
//      someday.  It'd be nice to get an API doc for free. For now a simple
//      'egrep ^function statslib.php' works pretty nicely


#return an array of all players' ids
function all_players(&$db) {
  $players = array();

  $result = sqlite_query($db,"select id from player");
  while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
    $players[] = $row["id"];
  }

  return $players;
}

#get player's name
function player_name(&$db, $id) {
  $result = sqlite_query($db,"select firstname from player where id=$id");
  $row = sqlite_fetch_array($result,SQLITE_ASSOC);
  return clean_value($row["firstname"]);
}

#get player's total number of wins
function player_total_wins(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from game ".
			 "where game.winner=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of losses
function player_total_losses(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from game ".
			 "where game.loser=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of games played
function player_total_games_played(&$db, $id) {
  return clean_value(player_total_wins($db, $id) +
		     player_total_losses($db, $id));
}

#get player's total points scored
function player_total_points_scored(&$db, $id) {
  return clean_value(player_total_points_scored_as_winner($db, $id) +
		     player_total_points_scored_as_loser($db, $id));
}

#get player's total points scored against
function player_total_points_scored_against(&$db, $id) {
  return clean_value(player_total_points_scored_against_as_winner($db, $id) +
		     player_total_points_scored_against_as_loser($db, $id));
}

#get player's total number of points scored as a loser
function player_total_points_scored_as_loser(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.loser_points) from game where ".
			 "game.loser=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of points scored as a winner
function player_total_points_scored_as_winner(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.winner_points) from game where ".
			 "game.winner=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of points scored against as winner
function player_total_points_scored_against_as_winner(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.loser_points) from game where ".
			 "game.winner=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of points scored against as loser
function player_total_points_scored_against_as_loser(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.winner_points) from game where ".
			 "game.loser=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of deuces
function player_total_deuces(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from point where ".
			 "point.scorer=$id and point.amount=2");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's number of points scored against as a winner
function player_points_scored_against_as_winner(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.loser_points) from game where ".
			 "game.winner=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's number of points scored against as a loser
function player_points_scored_against_as_loser(&$db, $id) {
  $result = sqlite_query($db,"select sum(game.winner_points) from game where ".
			 "game.loser=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of good bruises
function player_total_bruises_successful(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from bruise where ".
			 "bruise.player=$id and bruise.success=1");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of missed bruises
function player_total_bruises_unsuccessful(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from bruise where ".
			 "bruise.player=$id and bruise.success=0");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of attempted bruises
function player_total_bruises_attempted(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from bruise where ".
			 "bruise.player=$id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's number of shutouts
function player_total_shutouts(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from game where ".
			 "game.winner=$id and game.loser_points=0");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get number of times a player has been shutout
function player_total_been_shutouts(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from game where ".
			 "game.loser=$id and game.loser_points=0");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of tetrises
function player_total_tetrises(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from point as point1, point as point2, point as point3, point as point4 where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point4.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point4.id=point3.id+1 and point4.amount=2 and point1.game=point2.game and point2.game=point3.game and point3.game=point4.game");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of turkeys
function player_total_turkeys(&$db, $id) {
  $result = sqlite_query($db,"select count(*) from point as point1, point as point2, point as point3 where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point1.game=point2.game and point2.game=point3.game");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0] - (player_total_tetrises($db, $id) * 2));
}

#get player's total number of times in turkey territory
function player_total_double_deuces_with_possible_turkey(&$db, $id) {
  #in this query, point0 is a placeholder for the point before the double
  #deuce.  This helps us avoid scoring a turkey as two double deuces
  #(2,[2),2]  <--- first double is in paranthesis and second is in brackets
  #FIXME: this query doesn't work on the first point in the DB
  $result = sqlite_query($db,"select count(*) from point as point0, point as point1, point as point2, point as point3 where ((point0.id+1=point1.id) and (point0.amount!=2 or point0.scorer!=$id or point0.game!=point1.game)) and point1.scorer=$id and point2.scorer=$id and point1.amount=2 and point2.amount=2 and point2.id=point1.id+1 and point3.id=point2.id+1 and point1.game=point2.game and point2.game=point3.game");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's overall current streak
function player_overall_current_streak(&$db, $id) {
  #get list of every game this player played in, sorted backwards by date
  $result = sqlite_query($db,"select winner, loser from game where winner=$id or loser=$id order by date desc");
  
  #figure out what happened in the last game
  $row1 = sqlite_fetch_array($result,SQLITE_ASSOC);
  if ($row1['winner'] == $id) {
    $tag = "W";
    $key = "winner";
  } else {
    $tag = "L";
    $key = "loser";
  }

  $count = 1;

  #count how many times whatever happend last game happened before that
  #without anything else happening
  while($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {
    if ($row[$key] == $id) {
      $count++;
    } else {
      break;
    }
  }

  #clean up count and return the number with a tag (W or L)
  $count = clean_value($count);
  return "$count $tag";
}

/******************************
 * Stats that reference balls *
 ******************************/

#return number of times this player has used this ball
function player_total_games_played_with_ball(&$db, $id, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";
  $loserfield  = "loser_ball$type";

  $result = sqlite_query($db,"select count(*) from game where (winner=$id and $winnerfield=$ball) or (loser=$id and $loserfield=$ball)");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#return number of times this player has won with this ball
function player_total_games_won_with_ball(&$db, $id, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";

  $result = sqlite_query($db,"select count(*) from game where winner=$id and $winnerfield=$ball");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_deuces_with_ball(&$db, $id, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";
  $loserfield  = "loser_ball$type";

  $result = sqlite_query($db, "select count(*) from point, game where point.game = game.id and point.amount=2 and ((game.winner=$id and point.scorer=game.winner and game.$winnerfield=$ball) or (game.loser=$id and point.scorer=game.loser and game.$loserfield=$ball))");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_overall_deuces_per_game_with_ball(&$db, $id, $ball) {
  $games_played_with_ball = player_total_games_played_with_ball($db,$id,$ball);
  if ($games_played_with_ball == 0) {
    return 0;
  }

  $deuces_scored_with_ball = player_total_deuces_with_ball($db, $id, $ball);
  return clean_value($deuces_scored_with_ball / $games_played_with_ball);
}

function player_overall_win_percentage_with_ball(&$db, $id, $ball) {
  $games_played_with_ball = player_total_games_played_with_ball($db,$id,$ball);
  if ($games_played_with_ball == 0) {
    return 0;
  }

  $games_won_with_ball = player_total_games_won_with_ball($db, $id, $ball);
  return clean_value($games_won_with_ball / $games_played_with_ball);
}

function player_overall_ball_use_percentage(&$db, $id, $ball) {
  $games_played = player_total_games_played($db, $id);
  if ($games_played == 0) {
    return 0;
  }

  $games_played_with_ball = player_total_games_played_with_ball($db,$id,$ball);
  return clean_value($games_played_with_ball / $games_played);
}

/***************************************
 * Stats that reference another player *
 ***************************************/

function player_total_wins_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from game where game.winner = $id and game.loser = $player");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_losses_versus(&$db, $id, $player) {
  //this is just the reverse of player_total_wins_versus
  return player_total_wins_versus($db, $player, $id);
}

function player_total_points_scored_as_winner_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select sum(game.winner_points) from game where game.winner = $id and game.loser = $player");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_points_scored_as_loser_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select sum(game.loser_points) from game where game.winner = $player and game.loser = $id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_points_scored_against_as_loser_versus(&$db, $id,
							    $player) {
  //this is the reverse of player_total_points_scored_as_winner_versus
  return player_total_points_scored_as_winner_versus($db, $player, $id);
}

function player_total_points_scored_against_as_winner_versus(&$db, $id,
							      $player) {
  //this is the reverse of player_total_points_scored_as_loser_versus
  return player_total_points_scored_as_loser_versus($db, $player, $id);
}

function player_total_points_scored_versus(&$db, $id, $player) {
  return player_total_points_scored_as_loser_versus($db, $id, $player) +
    player_total_points_scored_as_winner_versus($db, $id, $player);
}

function player_total_points_scored_against_versus(&$db, $id, $player) {
  //this is the reverse of player_total_points_scored_versus
  return player_total_points_scored_versus($db, $player, $id);
}  

function player_total_deuces_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from game, point where game.id = point.game and point.scorer = $id and point.amount = 2 and ((game.winner = $player and game.loser = $id) or (game.loser = $player and game.winner = $id))");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_deuces_against_versus(&$db, $id, $player) {
  return player_total_deuces_versus($db, $player, $id);
}

function player_total_shutouts_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from game where game.winner = $id and game.loser = $player and game.loser_points=0");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_been_shutouts_versus(&$db, $id, $player) {
  //this is the reverse of player_total_shutouts_versus
  return player_total_shutouts_versus($db, $player, $id);
}

function player_total_bruises_successful_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from bruise, game where ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player)) and game.id = bruise.game and bruise.player = $id and bruise.success = 1");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_bruises_missed_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from bruise, game where ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player)) and game.id = bruise.game and bruise.player = $id and bruise.success != 1");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}  

function player_total_bruises_attempted_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from bruise, game where ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player)) and game.id = bruise.game and bruise.player = $id");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

function player_total_games_played_versus(&$db, $id, $player) {
  return player_total_wins_versus($db, $id, $player) +
    player_total_losses_versus($db, $id, $player);
}

#get player's total number of tetrises
function player_total_tetrises_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from game, point as point1, point as point2, point as point3, point as point4 where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point4.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point4.id=point3.id+1 and point4.amount=2 and point1.game=point2.game and point2.game=point3.game and point3.game=point4.game and point1.game = game.id and ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player))");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0]);
}

#get player's total number of turkeys
function player_total_turkeys_versus(&$db, $id, $player) {
  $result = sqlite_query($db,"select count(*) from game, point as point1, point as point2, point as point3 where point1.scorer=$id and point2.scorer=$id and point3.scorer=$id and point1.amount=2 and point2.id=point1.id+1 and point2.amount=2 and point3.id=point2.id+1 and point3.amount=2 and point1.game=point2.game and point2.game=point3.game and point1.game = game.id and ((game.winner = $id and game.loser = $player) or (game.loser = $id and game.winner = $player))");
  $row = sqlite_fetch_array($result,SQLITE_NUM);
  return clean_value($row[0] - (player_total_tetrises($db, $id) * 2));
}

/******************************************
 * Overall Stats that require calculation *
 ******************************************/

function player_overall_win_percentage(&$db, $id) {
  $games_played = player_total_games_played($db, $id);

  if ($games_played == 0) {
    return 0;
  }

  $total_wins = player_total_wins($db, $id);
  return clean_value($total_wins / $games_played);
}

function player_overall_points_per_game(&$db, $id) {
  $games_played = player_total_games_played($db, $id);

  if ($games_played == 0) {
    return 0;
  }

  $total_points = player_total_points_scored($db, $id);
  return clean_value($total_points / $games_played);
}

function player_overall_points_against_per_game(&$db, $id) {
  $games_played = player_total_games_played($db, $id);

  if ($games_played == 0) {
    return 0;
  }

  $total_points_against = player_total_points_scored_against($db, $id);
  return clean_value($total_points_against / $games_played);
}

function player_overall_deuces_per_game(&$db, $id) {
  $games_played = player_total_games_played($db, $id);

  if ($games_played == 0) {
    return 0;
  }

  $total_deuces = player_total_deuces($db, $id);
  return clean_value($total_deuces / $games_played);
}

function player_overall_points_per_game_as_loser(&$db, $id) {
  $total_losses = player_total_losses($db, $id);

  if ($total_losses == 0) {
    return 0;
  }

  $total_points_as_loser = player_total_points_scored_as_loser($db, $id);
  return clean_value($total_points_as_loser / $total_losses);
}

function player_overall_points_against_per_game_as_winner(&$db, $id) {
  $total_wins = player_total_wins($db, $id);

  if ($total_wins == 0) {
    return 0;
  }

  $total_points_against_as_winner =
    player_total_points_scored_against_as_winner($db, $id);

  return clean_value($total_points_against_as_winner / $total_wins);
}

function player_overall_bruise_percentage(&$db, $id) {
  $total_bruise_attempts = player_total_bruises_attempted($db, $id);

  if ($total_bruise_attempts == 0) {
    return 0;
  }

  $good_bruises = player_total_bruises_successful($db, $id);
  return clean_value($good_bruises / $total_bruise_attempts);
}

function player_overall_bruise_attempts_per_game(&$db, $id) {
  $total_games_played = player_total_games_played($db, $id);
  
  if ($total_games_played == 0) {
    return 0;
  }

  $total_bruise_attempts = player_total_bruises_attempted($db, $id);
  return clean_value($total_bruise_attempts / $total_games_played);
}

function player_overall_double_deuce_to_turkey_conversion_percentage(&$db,
								     $id) {
  $total_turkey_territories =
    player_total_double_deuces_with_possible_turkey($db, $id);

  if ($total_turkey_territories == 0) {
    return 0;
  }

  $total_turkeys = player_total_turkeys($db, $id);
  return clean_value($total_turkeys / $total_turkey_territories);
}

/***************************************************************
 * Stats that reference another player and require calculation *
 ***************************************************************/

function player_overall_win_percentage_versus(&$db, $id, $player) {
  $total_games_played_versus =
    player_total_games_played_versus($db, $id, $player);
  
  if ($total_games_played_versus == 0) {
    return 0;
  }

  $total_wins_versus = player_total_wins_versus($db, $id, $player);
  return clean_value($total_wins_versus / $total_games_played_versus);
}

function player_overall_deuces_per_game_versus(&$db, $id, $player) {
  $total_games_played_versus = 
    player_total_games_played_versus($db, $id, $player);
  
  if ($total_games_played_versus == 0) {
    return 0;
  }

  $total_deuces_versus = player_total_deuces_versus($db, $id, $player);
  return clean_value($total_deuces_versus / $total_games_played_versus);
}

function player_overall_deuces_against_per_game_versus(&$db, $id, $player) {
  //this is the reverse of player_overall_deuces_per_game_versus
  return player_overall_deuces_per_game_versus($db, $player, $id);
}

function player_overall_points_scored_per_game_versus(&$db, $id, $player) {
  $total_games_played_versus = 
    player_total_games_played_versus($db, $id, $player);
  
  if ($total_games_played_versus == 0) {
    return 0;
  }

  $total_points_scored_versus =
    player_total_points_scored_versus($db, $id, $player);
  return clean_value($total_points_scored_versus / $total_games_played_versus);
}
  
function player_overall_points_scored_against_per_game_versus(&$db, $id,
							      $player) {
  //this is the reverse of player_overall_points_scored_per_game_versus
  return player_overall_points_scored_per_game_versus($db, $player, $id);
}

function player_overall_bruise_percentage_versus(&$db, $id, $player) {
  $total_bruises_attempted_versus =
    player_total_bruises_attempted_versus($db, $id, $player);

  if ($total_bruises_attempted_versus == 0) {
    return 0;
  }

  $total_bruises_successful_versus =
    player_total_bruises_successful_versus($db, $id, $player);

  return clean_value($total_bruises_successful_versus /
		     $total_bruises_attempted_versus);
}

function player_overall_bruises_attempted_per_game_versus(&$db, $id, $player) {
  $total_games_played_versus = 
    player_total_games_played_versus($db, $id, $player);
  
  if ($total_games_played_versus == 0) {
    return 0;
  }

  $total_bruises_attempted_versus =
    player_total_bruises_attempted_versus($db, $id, $player);
  return clean_value($total_bruises_attempted_versus /
		     $total_games_played_versus);
}

function player_total_games_played_percent_versus(&$db, $id, $player) {
  $total_games_played_versus =
    player_total_games_played_versus($db, $id, $player);

  if ($total_games_played_versus == 0) {
    return 0;
  }  

  $total_games_played = player_total_games_played($db, $id);
  return clean_value($total_games_played_versus / $total_games_played);
}

/***************************************
 * functions that deal with ball color *
 ***************************************/

#get an array of all of the ball IDs
function all_balls(&$db) {
  $balls = array();

  $result = sqlite_query($db,"select id from ball");
  while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
    $balls[] = $row["id"];
  }

  return $balls;
}

#get an array of all of the bruiser's ball IDs
function all_bruisers(&$db) {
  $bruiser_num = 1;

  $balls = array();
  $result = sqlite_query($db, "select id from ball where num=$bruiser_num");
  while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
    $balls[] = $row["id"];
  }

  return $balls;
}

#get an array of all of the small ball's ball IDs
function all_small_balls(&$db) {
  $small_num = 2;

  $balls = array();
  $result = sqlite_query($db, "select id from ball where num=$small_num");
  while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
    $balls[] = $row["id"];
  }

  return $balls;
}

#get the string representation of the ball color
function ball_color(&$db, $ball) {
  $result = sqlite_query($db, "select color from ball where id=$ball");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
  return clean_value($row[0]);
}

#get the type of the ball as an integer
function ball_type(&$db, $ball) {
  $result = sqlite_query($db, "select num from ball where id=$ball");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
  return clean_value($row[0]);
}
  
#return the name for the ball type given its integer representation
function ball_type_name($type) {
  switch($type) {
  case 1:
    return "Bruiser";
    break;
  case 2:
    return "Scout";
    break;
  default:
    return "Ball";
    break;
  }
}

#return the total number of games that have been played with this ball
function ball_total_games_played(&$db, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";
  $loserfield  = "loser_ball$type";

  $result = sqlite_query($db, "select count(*) from game where ($winnerfield=$ball or $loserfield=$ball)");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
  return clean_value($row[0]);
}

#return the total number of games that have been won with this ball
function ball_total_games_won(&$db, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";

  $result = sqlite_query($db, "select count(*) from game where $winnerfield=$ball");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
  return clean_value($row[0]);
}  

#number of deuces scored with this ball color
function ball_total_deuces_scored(&$db, $ball) {
  $type = ball_type($db, $ball);
  $winnerfield = "winner_ball$type";
  $loserfield  = "loser_ball$type";

  $result = sqlite_query($db, "select count(*) from point, game where point.game = game.id and point.amount=2 and ((point.scorer=game.winner and game.$winnerfield=$ball) or (point.scorer=game.loser and game.$loserfield=$ball))");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
  return clean_value($row[0]);
}

#return the deuces per game when played with a certain ball
function ball_overall_deuces_per_game(&$db, $ball) {
  $ball_total_games_played = ball_total_games_played($db, $ball);
  
  if ($ball_total_games_played == 0) {
    return 0;
  }

  $ball_total_deuces = ball_total_deuces_scored($db, $ball);
  return clean_value($ball_total_deuces / $ball_total_games_played);
}

#return the frequency which this ball is played with overall
function ball_overall_use_percentage(&$db, $ball) {
  $universe_num_games = universe_total_games_played($db);

  if ($universe_num_games == 0) {
    return 0;
  }

  $ball_total_games_played = ball_total_games_played($db, $ball);
  return clean_value($ball_total_games_played / $universe_num_games);
}

#return the win percentage of this ball
function ball_overall_win_percentage(&$db, $ball) {
  $ball_total_games_played = ball_total_games_played($db, $ball);

  if ($ball_total_games_played == 0) {
    return 0;
  }

  $ball_games_won = ball_total_games_won($db, $ball);

  return clean_value($ball_games_won / $ball_total_games_played);
}

/*************************************************
 * functions that deal with universe-wide things *
 *************************************************/

#return the total number of games ever played
function universe_total_games_played(&$db) {
  $result = sqlite_query($db, "select count(*) from game");
  $row = sqlite_fetch_array($result, SQLITE_NUM);
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