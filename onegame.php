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

require("db.php");
require("boccelib.php");
require("header.php");
require("side.php");

$game = $_GET["game"];
db_open();
$result = db_query("select * from player");

$players = array();

$result = db_query("select winner,loser,winner_points,loser_points,strftime('%s',date) as date from game where id=$game");
$row = db_fetch_array($result);
$winner = $row["winner"];
$loser = $row["loser"];
$winner_score = $row["winner_points"];
$loser_score = $row["loser_points"];
$date = date("M jS, Y @ g:i a",$row["date"]);

$names = getNames($winner, $loser);
$winner_name = $names[0];
$loser_name = $names[1];

print "<div class=\"body\">";
print "<h1>$winner_name vs. $loser_name</h1>";
print "<h2>$date</h2>";
print "<br />";
print "<h3>Score History</h3>";
print "<table class=\"scoreboard\">";

$roundtr = "<tr><th>Round</th>";
$winnertr = "<tr><th>$winner_name</th>";
$losertr = "<tr><th>$loser_name</th>";

$result = db_query("select * from point where game=$game order by round");
while ($row = db_fetch_array($result)) {
  $scorer = $row["scorer"];
  $amount = $row["amount"];
  $round = $row["round"];

  $roundtr .= "<th>$round</th>";
  if ($scorer == $winner) {
    $winnertr .= "<td>$amount</td>";
    $losertr .= "<td>0</td>";
  } else {
    $winnertr .= "<td>0</td>";
    $losertr .= "<td>$amount</td>";
  }
 }
$roundtr .= "<th>Final</th></tr>\n";
$winnertr .= "<td>$winner_score</td></tr>\n";
$losertr .= "<td>$loser_score</td></tr>";

print $roundtr;
print $winnertr;
print $losertr;
print "</table>";

$result = db_query("select * from bruise where game=$game order by round");
if (db_num_rows($result) > 0) {
  print '<h3>Bruises</h3>';
  print '<table class="bruises">';
  
  print '<tr><th>Name</th><th>Round</th><th>Success</th></tr>';

  while ($row = db_fetch_array($result)) {

    //get name of bruiser
    $name = "";
    if ($row['player'] == $winner) {
      $name = $winner_name;
    } else {
      $name = $loser_name;
    }

    print "<tr><td>$name</td>";

    print "<td>" . $row['round'] . "</td";

    print "<td>";
    if ($row['success'] == 1) {
      print "Successful";
    } else {
      print "Failure";
    }
    print "</td>";
  }
  print "</table>";
}

db_close();
?>
</div>
</body>
</html>