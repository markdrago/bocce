<?
/*
 * Copyright (C) 2005 Josef "Jeff" Sipek
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

function league_comp($row1, $row2)
{
	return strcmp($row1['name'],$row2['name']);
}

db_open();
$leagues = db_query("select distinct l1.league, league.name, season.id from league_player as l1, league_player as l2, league, season where (l1.player=".$_SESSION["player1"]." and l2.player=".$_SESSION["player2"].") and l1.league=l2.league and l1.league=league.id and season.league=league.id;");

$l = Array();
switch (db_num_rows($leagues)) {
	default:
		// league or ...
		while($row = db_fetch_array($leagues))
			$l[] = Array(
				'season' => $row['id'],
				'name' => $row['name'] . " (season #" . $row['id'] . ")");
	case 0:
		// exhibition
		$l[] = Array(
			'season' => 0,
			'name' => 'Exhibition game (no season)');
		break;
}
db_close();

usort($l, 'league_comp');
$page->assign("seasons", $l);

$page->assign('subtitle', "Select League and Season");

$page->display('sel_season.tpl');

?>

