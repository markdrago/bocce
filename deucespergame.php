<?php
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
require("statslib.php");
include ($jpgraph_dir . '/src/jpgraph.php');
include ($jpgraph_dir . '/src/jpgraph_bar.php');

$db = sqlite_open($database_file);

$dataY = array();
$names = array();

foreach (all_players($db) as $player) {
  $names[] = player_name($db, $player);
  $dataY[] = player_overall_deuces_per_game($db, $player);
}

#actually create the picture

#Create the graph. These two calls are always required
$graph = new Graph(350, 250,"auto");    
$graph->SetScale( "textlin");

#Create the linear plot
$bplot =new BarPlot($dataY);
$bplot ->SetColor("blue");
$bplot ->SetWidth(".75");
$bplot ->value ->Show();
$bplot ->value ->SetFormat("%01.2f");
$bplot ->SetValuePos("center");

#Add the plot to the graph
$graph->Add( $bplot);
$graph ->xaxis ->SetTickLabels($names);
$graph ->title ->Set("Deuces per Game");

#Display the graph
$graph->Stroke();
?>