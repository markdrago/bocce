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

db_open();

#get a list of leagues that this player manages
$leagues_managed = leagues_with_manager($player_id);
if (count($leagues_managed) > 0) {
  $page->assign('leagues_managed', $leagues_managed);
}

#get a list of leagues that this player is in
$leagues = leagues_with_player($player_id);
if (count($leagues) > 0) {
  #remove managed leagues from regular leagues
  foreach ($leagues_managed as $manage_key => $managed_league) {
    foreach ($leagues as $player_key => $player_league) {
      if ($managed_league["id"] == $player_league["id"]) {
	unset($leagues[$player_key]);
      }
    }
  }
  
  #if there are still regular leagues left, then add it to the page
  if (count($leagues) > 0) {
    $page->assign('leagues', $leagues);
  }
}

db_close();

$page->assign('notice', $notice);
$page->assign('subtitle', "Manage Leagues");

$page->display('manage_leagues.tpl');

?>