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

require("boccelib.php");
require("header.php");
require("side.php");
?>
<div class="body">
<h1>Office Bocce Games</h1>
<table class="allgames">
<tr><th>Winner</th><th>Loser</th><th>Score</th><th>Date & Time</th></tr>
<?

$db = sqlite_open($database_file);
$result = sqlite_query($db,"select * from player");

$players = array();

while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
  $players[$row["id"]] = $row["firstname"];
 }

$result = sqlite_query($db,"select id,winner,loser,winner_points,loser_points,strftime('%s',date) as date from game order by date desc");
while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
  $winner_name = $players[$row["winner"]];
  $loser_name = $players[$row["loser"]];
  $score = $row["winner_points"] . " - " . $row["loser_points"];
  $date = date("M jS, Y @ g:i a",$row["date"]);
  $id = $row["id"];
  echo "<tr><td>$winner_name</td><td>$loser_name</td><td>$score</td><td><a href='onegame.php?game=$id'>$date</a></td></tr>";
 }
sqlite_close($db);
?>
</table>
</div>
</body>
</html>