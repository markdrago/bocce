<?
/*
 * Copyright (C) 2005 Mark Drago
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

function league_score($id)
{
	$players = array();

	$result = db_query("select ((max(game.dts) - min(game.dts)) / (3600.0 * 24.0 * 7.0)) as score from game" .
			__game_from_clause($type). " where " .
			__game_where_clause($type, $type_value));
	$row = db_fetch_array($result);
/*echo 
			__game_from_clause(STAT_LEAGUE). " where " .
			__game_where_clause(STAT_LEAGUE, $id);*/
	return $row['score'];
}

$page->assign('subtitle',"League Statistics");

$leagues = Array();
$rank = 1;

db_open();

$res = db_query("SELECT id, name FROM league ORDER BY name;");
while($row = db_fetch_array($res))
	$leagues[] = Array(
		'rank' => $rank++,
		'id' => $row['id'],
		'name' => $row['name'],
		'score' => league_score($row['id']));

db_close();

$page->assign('leagues',$leagues);
$page->display('league_stats.tpl');

?>
