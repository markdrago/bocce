<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2004 Tom Melendez
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

session_start();
require('boccelib.php');

/* CurrentScore returns the total score of a player at any given time.
 * The score is only stored as in each round, not as a total score.  This
 * is done to avoid storing the same data in two places.  This function
 * just adds up all of the scores in each round and returns the total. */
function CurrentScore($playernum) {
  if (!isset($_SESSION["history$playernum"])) {
    return 0;
  }

  $score = 0;
  foreach($_SESSION["history$playernum"] as $point) {
    $score += $point;
  }

  return $score;
}

if (!isset($_SESSION["player1"]) or !isset($_SESSION["player2"])) {
  redirect("startgame.php");
  return 0;
}

if (!isset($_SESSION['history1'])) {
  $_SESSION['history1'] = array();
  $_SESSION['history2'] = array();
  $_SESSION['bruises'] = array();
  $_SESSION['round'] = 0;
}

$confirmed = 0;

if (isset($_POST["action"])) {
  $round = $_SESSION['round'];
  if ($_POST["action"] == "Player1Score1") {
    $_SESSION["history1"][$round] = 1;
    $_SESSION["history2"][$round] = 0;
    $_SESSION['round']++;
  } elseif ($_POST["action"] == "Player1Score2") {
    $_SESSION["history1"][$round] = 2;
    $_SESSION["history2"][$round] = 0;
    $_SESSION['round']++;
  } elseif ($_POST["action"] == "Player2Score1") {
    $_SESSION["history2"][$round] = 1;
    $_SESSION["history1"][$round] = 0;
    $_SESSION['round']++;
  } elseif ($_POST["action"] == "Player2Score2") {
    $_SESSION["history2"][$round] = 2;
    $_SESSION["history1"][$round] = 0;
    $_SESSION['round']++;
  } elseif ($_POST["action"] == "Player1GoodBruise") {
    $_SESSION['bruises'][] = array($_SESSION['player1'],$round,1);
  } elseif ($_POST["action"] == "Player1BadBruise") {
    $_SESSION['bruises'][] = array($_SESSION['player1'],$round,0);
  }  elseif ($_POST["action"] == "Player2GoodBruise") {
    $_SESSION['bruises'][] = array($_SESSION['player2'],$round,1);
  } elseif ($_POST["action"] == "Player2BadBruise") {
    $_SESSION['bruises'][] = array($_SESSION['player2'],$round,0);
  } elseif ($_POST["action"] == "confirm") {
    $confirmed = 1;
  } elseif ($_POST["action"] == "undo") {
    //undo bruise or last scored point
    $bruiseInThisRound = false;
    foreach($_SESSION['bruises'] as $bruise) {
      if ($bruise[1] == $round) {
	$bruiseInThisRound = true;
	break;
      }
    }

    if ($bruiseInThisRound) {
      $_SESSION["bruises"] = array_slice($_SESSION["bruises"], 0,
					 count($_SESSION["bruises"]) - 1);
    } else {
      $_SESSION["history1"] = array_slice($_SESSION["history1"], 0,
					  count($_SESSION["history1"]) - 1);
      $_SESSION["history2"] = array_slice($_SESSION["history2"], 0,
					  count($_SESSION["history2"]) - 1);
      $_SESSION['round']--;
    }
  }
 }

$score1 = CurrentScore(1);
$score2 = CurrentScore(2);

if (!$confirmed) {
  
  //displaying confirmation page if the game would end
  $confirmPage = false;
  if ($score1 >= 7) {
    $score1 = 7;
    $confirmPage = true;
  }
  if ($score2 >= 7) {
    $score2 = 7;
    $confirmPage = true;
  }

  $names = getNames($_SESSION['player1'], $_SESSION['player2']);
  $name1 = $names[0];
  $name2 = $names[1];

  require("header.php");
  require("side.php");
?>
<div class="body">
<form action="" method="post">
<center>
<table>
<tr>
<td><h1 class="score"><?=$score1?></h1></td>
<td><h1 class="score">-</h1></td>
<td><h1 class="score"><?=$score2?></h1></td>
</tr>
<tr>
<td><h2><?=$name1?></h2></td>
<td></td>
<td><h2><?=$name2?></h2></td>
</tr>
</table>
<br />
<table class="controls">
<?
   if (!$confirmPage) {
?>
<tr><td>
<table class="controlpad">
<tr>
<td><?=$name1?></td>
<td><button type="submit" name="action" value="Player1Score1">Score 1</button></td>
<td><button type="submit" name="action" value="Player1Score2" >Deuce</button></td>
<td><button type="submit" name="action" value="Player1GoodBruise" >Successful Bruise</button></td>
<td><button type="submit" name="action" value="Player1BadBruise" >Missed Bruise</button></td>
</tr>
<tr>
<td><?=$name2?></td>
<td><button type="submit" name="action" value="Player2Score1">Score 1</button></td>
<td><button type="submit" name="action" value="Player2Score2" >Deuce</button></td>
<td><button type="submit" name="action" value="Player2GoodBruise" >Successful Bruise</button></td>
<td><button type="submit" name="action" value="Player2BadBruise" >Missed Bruise</button></td>
</tr>
</table>
</td>
</tr>
<?
   }

   if ((count($_SESSION['history1']) > 0) or (count($_SESSION['bruises']))) {
     print '<tr><td colspan="5"><button type="submit" name="action" value="undo">Undo</button></td></tr>';
   }
   if ($confirmPage) {
     print '<tr><td colspan="5"><button type="submit" name="action" value="confirm">Confirm</button></td></tr>';
   }

?>
</table>

<?
   if (count($_SESSION['history1'])) {
     print '<h3>Score History</h3>';
     print '<table class="scoreboard">';
     print '<tr>';
     
     print "<td>$name1</td>";
     foreach ($_SESSION['history1'] as $history) {
       print "<td>$history</td>";
     }
     
     print '</tr>';
     print '<tr>';
     
     print "<td>$name2</td>";
     foreach ($_SESSION['history2'] as $history) {
       print "<td>$history</td>";
     }
     print '</tr>';
     print '</table>';
   }

  if (count($_SESSION['bruises'])) {
    print '<h3>Bruises</h3>';
    print '<table class="bruises">';

    print '<th>Name</th><th>Round</th><th>Success</th>';

    foreach($_SESSION['bruises'] as $bruise) {
      print '<tr>';
      if ($bruise[0] == $_SESSION['player1']) {
	$name = $name1;
      } else {
	$name = $name2;
      }
      print "<td>$name</td>";
      $round = $bruise[1] + 1;
      print "<td>" . $round . "</td>";
      if ($bruise[2] == 1) {
	$success = "Successful";
      } else {
	$success = "Failure";
      }
      print "<td>$success</td>";
      print "</tr>";
    }

    print '</table>';
  }
?>

</center>
</form>
</div>
</body>
</html>
<?
  } else {
  
    //if either score is greater than 7 and confirmed == true
  
    $names = getNames($_SESSION['player1'], $_SESSION['player2']);
    $name1 = $names[0];
    $name2 = $names[1];

    $db = sqlite_open($database_file);
    $winner_score = 7;
  
    if ($score1 >= 7) {
      $winner = $_SESSION['player1'];
      $loser = $_SESSION['player2'];
      $loser_score = $score2;
      $winner_history = $_SESSION['history1'];
      $loser_history = $_SESSION['history2'];
      $winner_name = $name1;
      $loser_name = $name2;
      $winner_ball1=$_SESSION['player1_ball1'];
      $winner_ball2=$_SESSION['player1_ball2'];
      $loser_ball1=$_SESSION['player2_ball1'];
      $loser_ball2=$_SESSION['player2_ball2'];
    } else {
      $winner = $_SESSION['player2'];
      $loser = $_SESSION['player1'];
      $loser_score = $score1;
      $winner_history = $_SESSION['history2'];
      $loser_history = $_SESSION['history1'];
      $winner_name = $name2;
      $loser_name = $name1;
      $winner_ball1=$_SESSION['player2_ball1'];
      $winner_ball2=$_SESSION['player2_ball2'];
      $loser_ball1=$_SESSION['player1_ball1'];
      $loser_ball2=$_SESSION['player1_ball2'];
    }

#    $query = "insert into game values(null, $loser,$winner,$loser_score,$winner_score,datetime('now'))";
    $query = "insert into game values(null, $loser,$winner,$loser_score,$winner_score,'$winner_ball1','$winner_ball2','$loser_ball1','$loser_ball2', datetime('now'))";
    $result = sqlite_query($db,$query);
    $game_id = sqlite_last_insert_rowid($db);
    
    //maybe cut this down to one long transaction
    for ($round = 0; $round < count($winner_history); $round++) {
      if ($winner_history[$round] != 0) {
	$amount = $winner_history[$round];
	$scorer = $winner;
      } else {
	$amount = $loser_history[$round];
	$scorer = $loser;
      }
      $roundnumber = $round + 1;
      $query = "insert into point values(null, $game_id, $roundnumber, $scorer, $amount)";
      $result = sqlite_query($db, $query);
    }
    
    //store bruises in database
    foreach($_SESSION['bruises'] as $bruise) {
      $player = $bruise[0];
      $roundnum = $bruise[1];
      $success = $bruise[2];

      $query = "insert into bruise values(null, $game_id, $roundnum, $player, $success)";
      $result = sqlite_query($db, $query);
    }

    sqlite_close($db);
    
require("header.php");
require("side.php");
?>
<div class="body">
<form action="" method="post">
<input type="hidden" name="stage" value="gameon" />
<center>
<h1 class="title">Final Score</h1><br />
<table>
<tr>
<td><h1><?=$winner_score?></h1></td>
<td colspan="2"><h1>-</h1></td>
<td><h1><?=$loser_score?></h1></td>
</tr>
<tr>
<td><h2><?=$winner_name?></h2></td>
<td colspan="2"></td>
<td><h2><?=$loser_name?></h2></td>
</tr>
</table>
<br />
<h3>Score History</h3>
<table class="scoreboard">
<tr>
<?
print "<td>$winner_name</td>";
foreach ($winner_history as $history) {
    print "<td>$history</td>";
}

?>
</tr>
<tr>
<?
print "<td>$loser_name</td>";
foreach ($loser_history as $history) {
    print "<td>$history</td>";
}

?>
</tr>
</table>
<?
  if (count($_SESSION['bruises'])) {
    print '<h3>Bruises</h3>';
    print '<table class="bruises">';

    print '<th>Name</th><th>Round</th><th>Success</th>';

    foreach($_SESSION['bruises'] as $bruise) {
      print '<tr>';
      if ($bruise[0] == $_SESSION['player1']) {
	$name = $name1;
      } else {
	$name = $name2;
      }
      print "<td>$name</td>";
      $round = $bruise[1] + 1;
      print "<td>" . $round . "</td>";
      if ($bruise[2] == 1) {
	$success = "Successful";
      } else {
	$success = "Failure";
      }
      print "<td>$success</td>";
      print "</tr>";
    }

    print '</table>';
  }
?>
</center>
</form>
</div>
</body>
</html>

<?
/*
    }
*/

}
?>
