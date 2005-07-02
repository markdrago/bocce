<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2004 Tom Melendez
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

require('start.php');

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
		$_SESSION['pallino_toss'][$round + 1] = $_SESSION['player1'];
	} elseif ($_POST["action"] == "Player1Score2") {
		$_SESSION["history1"][$round] = 2;
		$_SESSION["history2"][$round] = 0;
		$_SESSION['round']++;
		$_SESSION['pallino_toss'][$round + 1] = $_SESSION['player1'];
	} elseif ($_POST["action"] == "Player2Score1") {
		$_SESSION["history2"][$round] = 1;
		$_SESSION["history1"][$round] = 0;
		$_SESSION['round']++;
		$_SESSION['pallino_toss'][$round + 1] = $_SESSION['player2'];
	} elseif ($_POST["action"] == "Player2Score2") {
		$_SESSION["history2"][$round] = 2;
		$_SESSION["history1"][$round] = 0;
		$_SESSION['round']++;
		$_SESSION['pallino_toss'][$round + 1] = $_SESSION['player2'];
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

	$page->assign('score1', $score1);
	$page->assign('score2', $score2);
	$page->assign('name1', $name1);
	$page->assign('name2', $name2);

	$page->assign('confirmPage', $confirmPage);

	if ((count($_SESSION['history1']) > 0) or (count($_SESSION['bruises']))) {
		$page->assign('undo', 1);
	}

	if (count($_SESSION['history1'])) {
		$hist1 = Array();
		foreach ($_SESSION['history1'] as $history) {
			$hist1[] = $history;
		}
		$page->assign('hist1', $hist1);
     
		$hist2 = Array();
		foreach ($_SESSION['history2'] as $history) {
			$hist2[] = $history;
		}
		$page->assign('hist2', $hist2);
		$page->assign('showhistory', 1);
	}

	if (count($_SESSION['bruises'])) {
		$bruises = Array();
		foreach($_SESSION['bruises'] as $bruise) {
			if ($bruise[0] == $_SESSION['player1']) {
				$name = $name1;
			} else {
				$name = $name2;
			}
			$bruises[] = Array(
				'name' => $name,
				'round' => $bruise[1] + 1,
				'succ' => (($bruise[2] == 1)?"Successful":"Failure"));
		}
	}
	$page->assign('bruises', $bruises);
	$page->assign('showbruises', 1);
	$template = "progress";
} else {
	//if either score is greater than 7 and confirmed == true
  
	$names = getNames($_SESSION['player1'], $_SESSION['player2']);
	$name1 = $names[0];
	$name2 = $names[1];

	db_open();
	$winner_score = 7;
	$coinflip_winner = $_SESSION["coinflip_winner"];
  
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

	$query = "insert into game (loser, winner, coinflip_winner, loser_points, winner_points, winner_ball1, winner_ball2, loser_ball1, loser_ball2, dts) values($loser,$winner,$coinflip_winner,$loser_score,$winner_score,'$winner_ball1','$winner_ball2','$loser_ball1','$loser_ball2', ".time().")";
	$result = db_query($query);
	$game_id = db_last_insert_rowid();
    
	//maybe cut this down to one long transaction
	for ($round = 0; $round < count($winner_history); $round++) {
		if ($winner_history[$round] != 0) {
			$amount = $winner_history[$round];
			$scorer = $winner;
		} else {
			$amount = $loser_history[$round];
			$scorer = $loser;
		}
		$pallino_tosser = $_SESSION['pallino_toss'][$round];
		$roundnumber = $round + 1;
		$query = "insert into point (game, round, scorer, pallino_tosser, amount) values ($game_id, $roundnumber, $scorer, $pallino_tosser, $amount)";
		$result = db_query($query);
	}
    
	//store bruises in database
	foreach($_SESSION['bruises'] as $bruise) {
		$player = $bruise[0];
		$roundnum = $bruise[1];
		$success = $bruise[2];

		$query = "insert into bruise (game, round, player, success) values ($game_id, $roundnum, $player, $success)";
		$result = db_query($query);
	}

	db_close();
    
	$page->assign('subtitle', "Final Score");

	$page->assign('winner_score', $winner_score);
	$page->assign('loser_score', $loser_score);
	$page->assign('winner_name', $winner_name);
	$page->assign('loser_name', $loser_name);

	$win_hist = Array();
	foreach ($winner_history as $history) {
		$win_hist[] = $history;
	}
	$page->assign('win_hist', $win_hist);

	$los_hist = Array();
	foreach ($loser_history as $history) {
		$los_hist[] = $history;
	}
	$page->assign('los_hist', $los_hist);

	if (count($_SESSION['bruises'])) {
		$bruises = Array();
		foreach($_SESSION['bruises'] as $bruise) {
			if ($bruise[0] == $_SESSION['player1']) {
				$name = $name1;
			} else {
				$name = $name2;
			}
			$bruises[] = Array(
			      	'name' => $name,
				'round' => $bruise[1] + 1,
				'suc' => (($bruise[2] == 1)?"Successful":"Failure"));
		}
	}
	$page->assign('bruises', $bruises);
	$template = "final";
}

$page->display("addscore-$template.tpl");

?>
