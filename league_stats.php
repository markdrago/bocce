<?
/*
 * Copyright (C) 2005 Mark Drago
 *           (C) 2005 Josef "Jeff" Sipek <jeffpc@josefsipek.net>
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

	$sql = "select max(game.dts) as max, min(game.dts) as min, count(distinct league_player.player) as players, count(distinct game.id) as games from game, league_player" .
			__game_from_clause(STAT_LEAGUE). " where " .
			"league.id=league_player.league and " .
			__game_where_clause(STAT_LEAGUE, $id);
	$result = db_query($sql);
	$row = db_fetch_array($result);
	if ($row['games'] == 0)
		return 0;

	switch ($row['games']) {
		case 0:
			return 0;
		default:
			return 100*(sqrt(log($row['games'], 2.71)) + log(($row['games']+2)/$row['players'], 2.71))*(1- ((time() - $row['max'])/(3600.0*24))/100.0);
	}

	/* if for whatever reason... */
	return 0;
}

function scorecomp($row1, $row2)
{
	return ($row1['score'] < $row2['score']);
}

$page->assign('subtitle',"League Statistics");

$leagues = Array();

db_open();

$res = db_query("SELECT id, name FROM league ORDER BY name;");
while($row = db_fetch_array($res))
	$leagues[] = Array(
		'rank' => 0,
		'id' => $row['id'],
		'name' => $row['name'],
		'score' => format_percent(league_score($row['id'])));

db_close();

usort($leagues, 'scorecomp');

for($rank = 0; $rank < count($leagues); $rank++)
	$leagues[$rank]['rank'] = $rank+1;

$page->assign('leagues',$leagues);
$page->display('league_stats.tpl');

?>
