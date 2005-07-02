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

$game = $_GET["game"];
db_open();
$result = db_query("select * from player");

$players = array();

$result = db_query("select winner,loser,winner_points,loser_points,dts as date from game where id=$game");
$row = db_fetch_array($result);
$winner = $row["winner"];
$loser = $row["loser"];
$winner_score = $row["winner_points"];
$loser_score = $row["loser_points"];
$date = date("M jS, Y @ g:i a",$row["date"]);

$names = getNames($winner, $loser);
$winner_name = $names[0];
$loser_name = $names[1];

$page->assign('subtitle', "$winner_name vs. $loser_name");
$page->assign('gamedate',$date);;
$page->assign('winner_name',$winner_name);
$page->assign('loser_name',$loser_name);

$scoredata = Array();
$result = db_query("select * from point where game=$game order by round");
while ($row = db_fetch_array($result)) {
	$scorer = $row["scorer"];
	$amount = $row["amount"];
	$round = $row["round"];

	if ($scorer == $winner) {
		$winner_pts = $amount;
		$loser_pts = 0;
	} else {
		$winner_pts = 0;
		$loser_pts = $amount;
	}
	$scoredata[] = Array(
		'round' => $round,
		'winner_pts' => $winner_pts,
		'loser_pts' => $loser_pts);
}
$scoredata[] = Array(
	'round' => 'Final',
	'winner_pts' => $winner_score,
	'loser_pts' => $loser_score);

$page->assign('scoredata',$scoredata);

$bruisedata = Array();
$result = db_query("select * from bruise where game=$game order by round");
if (db_num_rows($result) > 0) {
	while ($row = db_fetch_array($result))
		$bruisedata[] = Array(
			'name' => ($row['player']==$winner?$winner_name:$loser_name),
			'round' => $row['round'] + 1,
			'success' => ($row['success']?"Successful":"Failure"));
}
$page->assign('bruisedata', $bruisedata);

db_close();

$page->display('onegame.tpl');

?>
