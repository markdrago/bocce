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

require("start.php");
require("league_tools.php");

side_panel($page, "LOGGED_IN");

$notice = "";

$player_id = $_SESSION["player_id"];

$league_name = "";
$league_invites = "";
$database_is_open = false;

if (isset($_POST["submit"])) {
  $league_name  = $_POST["league_name"];
  $league_invites = $_POST["league_invites"];

  if ($notice == "") {
    $requiredarray = array("You must enter a name " .
			   "for the league."=>$league_name);

    $notice = checkrequired($requiredarray);
  }

  if ($notice == "") {
    $lengtharray = array("Your league name must be between 1 and 100 " .
			 "characters in length."=>array($league_name,1,100));

    $notice = checklength($lengtharray);
  }

  if ($notice == "") {
    $database_is_open = true;
    db_open();
    
    $query = "select count(*) from league where name='$league_name'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $count = $row[0];
    if ($count > 0) {
      $notice = "A league with this name already exists";
    }
  }

  #if we got here, create the league
  if ($notice == "") {
    $current_time = time();

    $query = "insert into league (name, manager, dts) values " .
      "('$league_name', '$player_id', '$current_time')";

    db_query($query);

    #get the league id of the league we just created
    $query = "select id from league where name='$league_name'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $league_id = $row[0];

    #add the creater of the league to the league itself
    $query = "insert into league_player (league, player, dts) values " .
      "('$league_id', '$player_id', '$current_time')";
    db_query($query);

    #add the invited players to the league_player_join_request table
    league_invite_players($league_id, $league_invites);

    $notice = "The league '$league_name' has been created";
  }

  if ($database_is_open == true) {
      db_close();
  }
}

$page->assign('league_name', $league_name);
$page->assign('league_invites', $league_invites);

$page->assign('notice', $notice);
$page->assign('subtitle', "Create a League");

$page->display('create_league.tpl');
