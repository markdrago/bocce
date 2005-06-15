<?php
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
require ($jpgraph_dir . '/src/jpgraph.php');
require ($jpgraph_dir . '/src/jpgraph_line.php');

#$id = $_GET["id"];
$num_games_in_average = 5;

#get all data for creating the picture
db_open();
$player = array();

#get names of all players
$result = db_query("select * from player");
while ($row = db_fetch_array($result)) {
  $player[$row["id"]] = array();
  $player[$row["id"]]["name"] = $row["firstname"];
  $player[$row["id"]]["id"] = $row["id"];
 }

#make the 'record' array for all players
foreach ($player as $key => $value) {
  $player[$key]["record"] = array();
}

#get record of all players
$result = db_query("select winner, loser from game order by dts");
while ($row = db_fetch_array($result)) {
  array_push($player[$row["winner"]]["record"], 1);
  array_push($player[$row["loser"]]["record"], 0);
}

$colors = array("", "blue", "red", "green", "yellow");

#Create the graph. These two calls are always required
$graph = new Graph(350, 250,"auto");    
$graph->SetScale("textlin",0,1);
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.875,'center','top');
$graph->xaxis->HideLabels();

$dataY=array();
foreach ($player as $person) {
  $dataY[$person["id"]] = array();
  $in_average = array();
  foreach ($person["record"] as $game) {
    array_push($in_average, $game);
    if (count($in_average) < $num_games_in_average) {
      continue;
    }
    $avg = array_sum($in_average) / $num_games_in_average;
    array_push($dataY[$person["id"]], $avg);
    array_shift($in_average);
  }

  #Create the linear plot
  $person["plot"] = new LinePlot($dataY[$person["id"]]);
  $person["plot"] -> SetColor($colors[$person["id"]]);
  $person["plot"] -> SetWeight(2);
  $person["plot"] -> SetLegend($person["name"]);

  #Add the plot to the graph
  $graph->Add($person["plot"]);

}


#foreach($lineplots as $lineplot) {
#  $graph->Add($lineplot);
#}

#array_push($dataY, $winpercentage);
#array_push($names, $person["name"]);

#actually create the picture

#$graph ->xaxis ->SetTickLabels($names);
$graph ->title ->Set("Rolling Win Percentage");

#Display the graph
$graph->Stroke();
?>
