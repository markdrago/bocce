<?
/*
 * Copyright (C) 2006 Mark Drago
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

require ('start.php');
require ('statslib.php');

$notice = "";

side_panel($page, "LOGGED_IN");

$player_id = $_SESSION["player_id"];

db_open();
    
#get general data about the player
$query = "select nickname, dts from player where id=$player_id";
$result = db_query($query);
$row = db_fetch_array($result);
$nick = $row[0];
$join_date = date("F jS, Y", $row[1]);


#get data about the leagues the player is in
$leagues = array();
$query = "select league.id, league.name from league, league_player where " .
  "league_player.player = $player_id and " .
  "league_player.league = league.id";
$result = db_query($query);
$got_leagues = false;
while (($row = db_fetch_array($result)) != NULL) {
  $id = $row[0];
  $name = $row[1];

  #get the player's win percentage in this league
  $win_percentage = player_overall_win_percentage($player_id, STAT_LEAGUE,$id);
  $win_percentage = format_percent($win_percentage);

  #append this league to the leagues array
  $league = array("id"=>$id, "name"=>$name, "win_percentage"=>$win_percentage);
  $leagues[] = $league;

  #indicate that we have at least one league
  $got_leagues = true;
}

#check to see if the player has been invited to join any leagues
$join_requests = join_requests_for_player($player_id);
if (count($join_requests) > 0) {
  #set up template stuff for join requests
  $page->assign('join_requests', $join_requests);
}

db_close();

$page->assign('subtitle', $nick);
$page->assign('join_date', $join_date);

if ($got_leagues == true) {
  $page->assign('leagues', $leagues);
}

$page->display('player_home.tpl');

function join_requests_for_player($player_id) {

  $query = "select request.id, league.name, manager.email " .
    "from league, player, player as manager, " .
    "league_player_join_request as request " .
    "where league.id=request.league and " .
    "manager.id=request.manager_requested and player.id=$player_id and " .
    "((request.player_exists=0 and request.email=player.email) or " .
    " (request.player_exists=1 and request.player_id=$player_id))";

  $result = db_query($query);

  $requests = array();
  while (($row = db_fetch_array($result)) != NULL) {
    $request_id = $row[0];
    $league_name = $row[1];
    $manager_email = $row[2];
    
    $requests[] = array("id" => $request_id,
			"league" => $league_name,
			"manager" => $manager_email);

  }

  return $requests;
}

?>
