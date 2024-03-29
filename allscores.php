<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
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

db_open();
$result = db_query("select * from player");

$players = array();

while ($row = db_fetch_array($result)) {
  $players[$row["id"]] = $row["firstname"];
 }

$data = Array();

$result = db_query("select id,winner,loser,winner_points,loser_points,dts as date from game order by dts desc");
while ($row = db_fetch_array($result)) {
  $winner_name = $players[$row["winner"]];
  $loser_name = $players[$row["loser"]];
  $score = $row["winner_points"] . " - " . $row["loser_points"];
  $date = date("M jS, Y @ g:i a",$row["date"]);
  $id = $row["id"];
  $data[] = Array(
  		'id' => $id,
  		'winner_name' => $winner_name,
		'loser_name' => $loser_name,
		'score' => $score,
		'date' => $date);
 }
db_close();

$page->assign('subtitle', 'Office Bocce Games');
$page->assign('data', $data);
$page->display('allscores.tpl');

?>
