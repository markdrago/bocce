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

require_once("boccelib.php");

function leagues_with_player($player_id)
{
  $leagues = array();
  $query = "select league.id, league.name from league, league_player where " .
    "league_player.player = $player_id and " .
    "league_player.league = league.id";
  $result = db_query($query);

  while (($row = db_fetch_array($result)) != NULL) {
    $id = $row[0];
    $name = $row[1];
    
    #append this league to the leagues array
    $league = array("id"=>$id,"name"=>$name);
    $leagues[] = $league;
  }

  return $leagues;
}

function leagues_with_manager($player_id)
{
  $leagues = array();
  $query = "select league.id, league.name from league where " .
    "league.manager = $player_id";
  $result = db_query($query);

  while (($row = db_fetch_array($result)) != NULL) {
    $id = $row[0];
    $name = $row[1];
    
    #append this league to the leagues array
    $league = array("id"=>$id,"name"=>$name);
    $leagues[] = $league;
  }

  return $leagues;
}

function league_invite_players($league_id, $league_invites) {

  if ($league_invites == "") {
    return FALSE;
  }

  $email_addrs = explode("\n", $league_invites);

  foreach ($email_addrs as $email) {
    #stuff that will be included in the join_request table
    $random_string = generate_random_string();
    $time = trim(time());
    $email = trim($email);

    #determine if there is a current player with this email address
    $query = "select count(*) from player where email='$email'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $count = intval($row[0]);

    /* FIXME: handle re-inviting someone */

    if ($count > 0) {
      #get the id of this existing player and add it to the table
      $query = "select id from player where email='$email'";
      $result = db_query($query);
      $row = db_fetch_array($result);
      $player_id = intval($row[0]);

      $query = "insert into league_player_join_request (league, " .
	"manager_requested, player_exists, player_id, random_key, dts) " .
	"values ($league_id, 1, 1, $player_id, '$random_string', $time)";
      db_query($query);

    } else {

      #add a user to the table even though they don't actually exist yet
      $query = "insert into league_player_join_request (league, " .
	"manager_requested, player_exists, email, random_key, dts) " .
	"values ($league_id, 1, 0, '$email', '$random_string', $time)";
      db_query($query);
    }

  }

}

?>